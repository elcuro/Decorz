<?php

    Configure::write('Decorz.prefix', 'decorz_');
    Configure::write('Decorz.ext', 'jpg');

    Croogo::hookComponent('Nodes', 'Decorz.Decorz');

?>