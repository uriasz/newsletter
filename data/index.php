<?php
// Bloquear acesso direto a arquivos JSON
header('HTTP/1.0 403 Forbidden');
exit('Acesso negado');
