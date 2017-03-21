<?php
namespace Tests\AutomationGrader;
/**
 * Class CholeskyDecomposition
 * @package AutomationGrader
 *
 */
class CholeskyDecomposition {
  /**
  * Decomposition storage
  * @var array
  * @access private
  */
  var $L = array();
  
  /**
  * Matrix row and column dimension
  * @var int
  * @access private
  */
  var $m;
  
  /**
  * Symmetric positive definite flag
  * @var boolean
  * @access private
  */
  var $isspd = true;
  
  /**
  * CholeskyDecomposition
  * Class constructor - decomposes symmetric positive definite matrix
  * @param mixed Matrix square symmetric positive definite matrix
  */
  function CholeskyDecomposition( $A = null ) {
    if( is_a($A, 'Matrix') ) {
      $this->L = $A->getArray();
      $this->m = $A->getRowDimension();
      
      for( $i = 0; $i < $this->m; $i++ ) {
        for( $j = $i; $j < $this->m; $j++ ) {
          for( $sum = $this->L[$i][$j], $k = $i - 1; $k >= 0; $k-- )
            $sum -= $this->L[$i][$k] * $this->L[$j][$k];

          if( $i == $j ) {
            if( $sum >= 0 ) {
              $this->L[$i][$i] = sqrt( $sum );
            } else {
              $this->isspd = false;
            }
          } else {
            if( $this->L[$i][$i] != 0 )
              $this->L[$j][$i] = $sum / $this->L[$i][$i];
          }
        }
      
        for ($k = $i+1; $k < $this->m; $k++)
          $this->L[$i][$k] = 0.0;
      }
    } else {
      trigger_error(ArgumentTypeException, ERROR);
    }
  }
  
  /** 
  * Is the matrix symmetric and positive definite?
  * @return boolean
  */
  function isSPD () {
    return $this->isspd;
  }
  
  /** 
  * getL
  * Return triangular factor.
  * @return Matrix Lower triangular matrix
  */
  function getL () {
    return new Matrix($this->L);
  }
  
  /** 
  * Solve A*X = B
  * @param $B Row-equal matrix
  * @return Matrix L * L' * X = B
  */
  function solve ( $B = null ) {
    if( is_a($B, 'Matrix') ) {
      if ($B->getRowDimension() == $this->m) {
        if ($this->isspd) {
          $X  = $B->getArrayCopy();
          $nx = $B->getColumnDimension();
          
          for ($k = 0; $k < $this->m; $k++) {
            for ($i = $k + 1; $i < $this->m; $i++)
              for ($j = 0; $j < $nx; $j++)
                $X[$i][$j] -= $X[$k][$j] * $this->L[$i][$k];
            
            for ($j = 0; $j < $nx; $j++)
              $X[$k][$j] /= $this->L[$k][$k];
          }
          
          for ($k = $this->m - 1; $k >= 0; $k--) {
            for ($j = 0; $j < $nx; $j++)
              $X[$k][$j] /= $this->L[$k][$k];
            
            for ($i = 0; $i < $k; $i++)
              for ($j = 0; $j < $nx; $j++)
                $X[$i][$j] -= $X[$k][$j] * $this->L[$k][$i];
          }
          
          return new Matrix($X, $this->m, $nx);
        } else {
          trigger_error(MatrixSPDException, ERROR);
        }
      } else {
        trigger_error(MatrixDimensionException, ERROR);
      }
    } else {
      trigger_error(ArgumentTypeException, ERROR);
    }
  }
}

?>
