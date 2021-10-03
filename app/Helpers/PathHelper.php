<?php

function adminAsset($path) {
    return asset('assets/admin/' . $path);
}

function userAsset($path) {
    return asset('assets/user/' . $path);
}

function imageAsset($path) {
    return asset('img/' . $path);
}