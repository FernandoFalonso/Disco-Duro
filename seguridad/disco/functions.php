<?php

function sesion() {
    session_name("disco");
    session_cache_limiter("nocache");
    session_start();
}

?>