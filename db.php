<?php

try {
            $database = new PDO ('mysql:host=localhost;dbname=lgx-motivation;charset=utf8','root','');
        } catch (Exception $e) {
            die ('Erreur :'. $e->getMessage());
        }
