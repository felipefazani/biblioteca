<?php 
    require_once 'senhas_admin.php';
    echo <<<_END
    <html>
        <body>
            <form action="registraEmprDevol.php" method="post">
                <fieldset>
                    <legend>Registro de Empréstimo/Devolução:</legend>
                    Usuario: <input type="text" name="usuario"><br>
                    <br>
                    Tombo: <input type="text" name="tombo"><br>
                    <br>
                    Data de Emprestimo: <input type="date" name="dataEmprestimo">
                    <br>
                    <br>
                    Data de Devolucao: <input type="date" name="dataDevolucao">
                    <br>  
                    <br>
                    <button type="submit" value="registroEmprestimo"> Registrar</button>
                </fieldset>
            </form>
            <button onclick="location.href = 'login.html';"> sair</button>
            
            <script>
                function registroAlerta() {
                    alert("Registrando no BD!");
                }
            </script>
        </body>
    </html>
_END;

    if(!empty($_POST["usuario"]) && !empty($_POST["tombo"]) && !empty($_POST["dataEmprestimo"]) && !empty($_POST["dataDevolucao"])){
        $conexão = new mysqli($servidor, $usuario, $senha, $bd);
        if ($conexão->connect_error) {
            die($conexão->connect_error);
        }

        $tombo	= $_POST["tombo"];
        $resultado = $conexão->query("SELECT titulo FROM livros WHERE tombo = '$tombo'");
        
        if($resultado->num_rows != 0) {
            $dataEmprestimo = date("Y-m-d",strtotime($_POST["dataEmprestimo"]));
            $dataDevolucao = date("Y-m-d",strtotime($_POST["dataDevolucao"]));
            $usuario = $_POST["usuario"];

            $query = "INSERT INTO emprestimos VALUES"."('','$tombo', '$dataEmprestimo','$dataDevolucao', '$usuario')";	
                
            $resultado 	= $conexão->query($query);

            if (!$resultado){ 
                echo "Erro ao inserir dados: $query<br>" . $conexão->error . "<br><br>";
            } else{
                

                echo <<<_END
                
                <script>
                    function registroAlerta() {
                        alert("Registrando no BD!");
                    }
                    registroAlerta();
                </script>
_END;

                echo "<br>registro incluido no BD:<br>";
                echo "Usuário: $usuario <br>";
                echo "Tombo:$tombo <br>";
                echo "Data Emprestimo:$dataEmprestimo <br>";
                echo "Data Devolucao:$dataDevolucao <br>";
            } 
        } else {
            echo "<br><br>Este livro não existe";
        }
    }
?>