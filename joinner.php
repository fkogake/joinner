<?php
//===================================
// Desafio do alagamento de Silhuetas
//===================================


/*
 * Autor: Fernando Kogake
 * Data: 24/02/2019
 * 
 */

if(isset($_POST['MAX_FILE_SIZE'])){
    // Grava o arquivo no servidor
    $nome_temporario = $_FILES["userfile"]["tmp_name"];
    $nome_real = $_FILES["userfile"]["name"];
    copy($nome_temporario,"$nome_real");
    
    // Abre o arquivo e coloca num array por linha
    $arquivo = "upload/".$nome_real;
    $fp = fopen($arquivo, 'r');
    $conteudo = fread($fp, filesize($arquivo));
    $linha = explode("\n", $conteudo);
    $resultado = array();
    
    $num_casos      = (int)$linha[0]*2; //primeira linha do arquivo onde está localizado a quantidade de casos no arquivo; eu multipliquei por dois para utilizar no loop
    
    for($i=1; $i<=$num_casos; $i++){
        if(($i % 2) == 1){
            $tamanho_array  = $linha[$i]; //primeira linha de cada caso
        } else {
            $string_arquivo = $linha[$i]; //segunda linha de cada caso
            $posicoes_array = array();
            $posicoes_array = explode(" ", $string_arquivo);
            $resposta = array();
            $array_menores = array();
            for($x=0; $x<$tamanho_array; $x++){
                if($x == 0){
                    $maior_valor    = $posicoes_array[$x];
                    $liga_borda_esquerda = 1;
                    $borda_esquerda = $maior_valor;
                    $liga_borda_direita  = 0;
                    $resposta[] = 0;
                    
                } else {
                    if($posicoes_array[$x] > $maior_valor){
                        if(count($array_menores) >= 1){
                            
                            $borda_direita = $posicoes_array[$x];
                            
                            $menor_topo = ($borda_esquerda < $borda_direita ? $borda_esquerda : $borda_direita);
                            for($y=0; $y<count($array_menores);$y++){
                               $resposta[] =  $menor_topo - $array_menores[$y];
                            }
                            $array_menores = array();
                        } 
                        $resposta[] = 0;
                       
                        $maior_valor = $posicoes_array[$x];
                        $borda_esquerda = $maior_valor;
                    } else {
                        $array_menores[] = $posicoes_array[$x];
                    }
                    
                    $ultima_posicao = (int)$tamanho_array-1;
                    if($x == $ultima_posicao){
                        if(count($array_menores) >= 1){
                            $borda_direita = max($array_menores);
                            $menor_topo = ($borda_esquerda < $borda_direita ? $borda_esquerda : $borda_direita);
                            for($y=0; $y<count($array_menores);$y++){
                                $resposta[] =  (int)$menor_topo - (int)$array_menores[$y];
                            }
                            $array_menores = array();
                        } else {
                            $resposta[] = 0;
                        }
                        $resposta[$ultima_posicao] = 0;
                    }
                }
            }
            $resultado[]= array_sum($resposta);
        }
    }
    $dados = (implode("\n", $resultado));
    $name = 'arquivo.txt';
    $file = fopen($name, 'w');
    fwrite($file, $dados);
    fclose($file);
    echo"<script>window.location='arquivo.txt';</script>";
} else {
?>

<form enctype="multipart/form-data" action="joinner.php" method="POST">
    <!-- MAX_FILE_SIZE deve preceder o campo input -->
    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
    <!-- O Nome do elemento input determina o nome da array $_FILES -->
    Enviar esse arquivo: <input name="userfile" type="file" />
    <input type="submit" value="Enviar arquivo" />
</form>


<?php } ?>