<?php

function uuid()
{
    return strtoupper(vsprintf('%s-%s-%s-%s', str_split(bin2hex(random_bytes(8)), 4)));
}