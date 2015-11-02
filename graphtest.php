<?php
//exec("dir", $return_data, $return_code);
//print("My path is " . print_r($return_data, TRUE));
//exec("C:\Progra~1\R\R-3.2.2\bin\Rscript C:\xampp\htdocs\php\twitter_to_r\data\twittergraph.r",$junk,$return_code);
//exec("C:\Progra~1\R\R-3.2.2\bin\Rscript twittergraph.r",$return_data,$return_code);
exec('C:\Progra~1\R\R-3.2.2\bin\Rscript C:\xampp\php\twittergraph.r',$return_data,$return_code);
print("Return code is " . $return_code);
