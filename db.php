<?php

try {
            $database = new PDO ('mysql:host=localhost;dbname=u864174266_lgxmotivation;charset=utf8','u864174266_lgxmotivation','lgxmotivation@Mpoed05');
        } catch (Exception $e) {
            die ('Erreur :'. $e->getMessage());
        }
