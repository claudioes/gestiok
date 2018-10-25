<?php

foreach (glob(ROOT . '/app/Routes/*.php') as $filename) {
    require $filename;
}