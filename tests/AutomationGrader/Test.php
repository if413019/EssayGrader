<?php
    require_once 'Matrix.php'; 

    // $matrix = array(
    //     array(
    //         0,
    //         0.15,
    //         0,
    //         0
    //     ),
    //     array(
    //         0,
    //         0,
    //         0.099,
    //         0.099
    //     )
    // );
    // $matrix = array(
    //     array(
    //         0.099,
    //         0.0,
    //         0.099,
    //         0.0
    //     ),
    //     array(
    //         0.0,
    //         0.1505,
    //         0.0,
    //         0.0
    //     )
    // );
    $matrix = array(
        array(
            0,
            0.15051499783199
        ),
        array(
            0.15051499783199,
            0
        ),
        array(
            0,
            0
        )
    );
    echo '<pre>';
    $matrix = new Matrix($matrix);
    print_r($matrix);
    $SVD = $matrix->svd();
    print_r($SVD);
    $s = $SVD->getU();
    print_r($s);
    die();