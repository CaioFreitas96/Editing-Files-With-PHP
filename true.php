
<?php

$URL = 'https://datawarehouse-true.s3-sa-east-1.amazonaws.com/teste-true/teste_true_term.zip'; 
# Destination
$destination = "teste_true_term.zip";

$filedata = file_get_contents($URL);
file_put_contents($destination, $filedata);
$dir = dirname(__FILE__);

$zip = new ZipArchive;
 
 $zip->open('teste_true_term.zip');
 $zip->extractTo($dir);
 $zip->close();

$delimitador = ',';
$cerca = '"';

 $encad = fopen("encad-termicas.csv", 'r');
 
    if($encad){

        $cabecalho = fgetcsv($encad, 0, $delimitador, $cerca);
        $linha1 = fgets($encad);
        $linha2 = fgets($encad);
        $linha3 = fgets($encad);
        $linha4 = fgets($encad);
        
        fclose($encad);
    }
    
    //ALTERANDO

    $arquivo = fopen('TERM.DAT', 'r+'); 
        
    if ($arquivo) { 
       
        while(true) { 
            
            $linha = fgets($arquivo); 
            
            if ($linha==null) break; 
            
            if(preg_match("/SEROPEDICA/", $linha)) { 
                $string .= str_replace($linha, $linha1, $linha); 
            }else if(preg_match("/TERMORIO/", $linha)){
                $string .= str_replace($linha, $linha2, $linha); 
            }else if(preg_match("/MAUA/", $linha)){
                $string .= str_replace($linha, $linha3, $linha); 
            }else if(preg_match("/APARECIDA/", $linha)){
                $string .= str_replace($linha, $linha4, $linha); 
            }else{ 
                 
                $string .= $linha; 
            } 
            
            echo "<br>";
            var_dump($string);
        }
        
        rewind($arquivo); 
         
        ftruncate($arquivo, 0); 
         
        fwrite($arquivo, $string);

        if (!fwrite($arquivo, $string)) 
        die('Não foi possível atualizar o arquivo.'); 
        
        echo 'Arquivo atualizado com sucesso'; 
        fclose($arquivo);
    } 
    //ZIPANDO ARQUIVOS
    $zip->open( 'teste_true.zip', ZipArchive::CREATE);

    // Adiciona um arquivo à pasta
    $zip->addFile( 
	'TERM.DAT', 
	'TERM.TRUE' 
    );
    $zip->addFile( 
        'encad-termicas.csv', 
        'encad-termica-editado.csv' 
        );

    $zip->close();
?>