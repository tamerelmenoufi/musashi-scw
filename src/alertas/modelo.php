<?php
    $home = true;
    include("../../includes/includes.php");


    $titulo = array(
                  't' => '',
                  'n' => 'Novo',
                  'p' => 'Pendente',
                  'c' => 'Concluído',
            );

    $parada = array(
        's' => 'SIM',
        'n' => 'NÃO'
        );

    $q = "SELECT
                    a.codigo,
                    a.status,
                    a.time,
                    a.motivo,
                    a.parada,
                    tm.nome as time_nome,
                    mt.nome as motivo_nome,
                    s.nome as setor,
                    m.nome as maquina,
                    t.nome as tipo_manutencao,
                    a.problema,
                    f.nome as funcionario,
                    tc.nome as tecnico
            FROM chamados a
                left join setores s on a.setor = s.codigo
                left join tipos_manutencao t on a.tipo_manutencao = t.codigo
                left join maquinas m on a.maquina = m.codigo
                left join time tm on a.time = tm.codigo
                left join motivos mt on a.motivo = mt.codigo
                left join login tc on a.tecnico = tc.codigo
                left join login f on a.funcionario = f.codigo
            where a.codigo = '{$_POST['codigo']}'";
        $r = mysql_query($q);
        $d = mysql_fetch_object($r);

        $msg = "<b>Cadastrado ID</b>: ".str_pad($d->codigo, 8, "0", STR_PAD_LEFT).
               "<br> <b>SETOR</b>: ".utf8_encode($d->setor).
               "<br> <b>MÁQUINA</b>: ".utf8_encode($d->maquina).
               "<br> <b>MÁQUINA PARADA</b>: <span style='color:red; font-weight:bold;'>".$parada[$d->parada]."</span>".
               "<br> <b>TIPO DE MANUTENÇÃO</b>: ".utf8_encode($d->tipo_manutencao).
               (($d->problema)?"<br> <b>PROBLEMA</b>: ".str_replace("\n"," ",utf8_encode($d->problema)):false).
               (($d->funcionario)?"<br> <b>FUNCIONÁRIO</b>: ".utf8_encode($d->funcionario):false).
               (($d->tecnico)?"<br> <b>TÉCNICO</b>: ".utf8_encode($d->tecnico):false).

               (($d->time_nome)?"<br> <b>TIME</b>: ".utf8_encode($d->time_nome):false).
               (($d->motivo_nome)?"<br> <b>MOTIVO</b>: ".utf8_encode($d->motivo_nome):false).


               (($d->status)?"<br> <b>SITUAÇÃO</b>: ".$titulo[$d->status]:false).
               (($d->observacao)?"<br> <b>OBSERVAÇÕES</b>: ".str_replace("\n"," ",$_POST['observacao']):false);


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body{
            font-family:'verdana';
            font-size:12px;
            color:#333;
        }
        .item{
            width:100%;
            padding:0px;
            margin:0;
            margin-top:5px;
            font-family:'verdana';
            font-size:12px;
            color:#333;
        }
        .item p{
            font-size:20px;
            color:#a1a1a1;
            padding:0px;
            margin:0;
            padding-bottom:5px;
            padding-top:15px;
        }
        .item div{
            width:100%;
            padding:0px;
            margin:0;
        }

        .item_foto{
            width:100%;
            padding:0px;
            margin:10px;
            margin-top:5px;
            font-family:'verdana';
            font-size:12px;
            color:#333;
            border-radius:7px;
            border:solid #ccc 2px;
            background-color:#eee;
            text-align:center;
        }

        .item_foto p{
            width:100%;
            padding:0px;
            margin:0;
            text-align:center;
        }
        .placas{
            display: flex;
            justify-content: space-between;
        }
        .placas div{
            width:25%;
            text-align: center;
            font-size: 15px;
            color:#fff;
            padding:20px;
            border-radius:5px;
        }
        th{
            text-align:left;
        }
        td{
            padding:5px;
        }
    </style>
</head>
<body>

    <table cellspacing="0" cellpadding="0" style="border:1px #ccc solid; width:600px;">
        <tr>
            <td style="width:120px">
                <img src="cid:musashi.png" style="width:120px" >
            </td>
            <td style="padding:10px;">
                <h2>Quadro de Situação SCW</h2>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="width:600px; padding:20px;">

                <?php
                /*
                ?>
                <div class="placas">
                    <div style="background-color:blue">
                        <b>11</b><br>Total de O.S.
                    </div>
                    <div style="background-color:red">
                        <b>12</b><br>O.S. Pendentes
                    </div>
                     <div style="background-color:green">
                        <b>13</b><br>O.S. Concluídas
                    </div>
                </div>
                <?php
                //*/
                ?>




                <div class="item">
                    <p>SCW-MUSASHI - Informativo (<?=(($d->status == 'novo')?"Um novo chamado":"Chamado com alteração")?>)</p>
                    <hr>
                    <div>
                        <table cellspacing="0" cellpadding="0" width="100%">
                            <tr style="background-color:<?=(($i%2 == 0)?'#ffffff':'#eee')?>">
                                <td><?=$msg?></td>
                            </tr>
                        </table>
                    </div>
                </div>






            </td>
        </tr>

    </table>
</body>
</html>