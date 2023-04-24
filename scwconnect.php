<?php

    function AppConnect($banco = "app"){
        mysql_connect("scw.mohatron.com:3308","root","SenhaDoBanco");
        mysql_select_db($banco);
    }

