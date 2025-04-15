<?php

function parseCommaSeparated($value) {
    return array_filter(array_map('trim', explode(',', $value)));
}