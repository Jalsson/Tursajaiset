<?php
// Always start this first
session_start();

session_destroy();

header("Location: /tursajaiset/vihjeet");
?>