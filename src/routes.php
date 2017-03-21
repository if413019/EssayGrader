<?php
// Routes

$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->post('/scoring', function ($request, $response, $args) {
    $files = $request->getUploadedFiles();
    if (empty($files['newfile'])) {
        $error = array(
            'error' => 'Tidak ada file penilaian'
        );
        $newResponse = $response->getBody()->write(json_encode($error));
        return $response->withStatus(400);
    }
 
    if(empty($request['stemming'])){
        $error = array(
            'error' => 'Tidak ada'
        );
        $newResponse = $response->getBody()->write(json_encode($error));
        return $response->withStatus(400);
    }

    $newfile = $files['newfile'];

	if ($newfile->getError() === UPLOAD_ERR_OK) {
        $grader = $this->get('grader');
    	$uploadFileName = $newfile->getClientFilename();
    	$newfile->moveTo("scoring.json");
        $string = file_get_contents("scoring.json");
        $scores = json_decode($string, true);
        $result = $grader->grade($scores);
        return $response->getBody()->write(json_encode($result));
	}
    // do something with $newfile
});