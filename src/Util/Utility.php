<?php


namespace App\Util;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class Utility
{
    const DEFAULT_ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';

    /**
     * Génère un nom unique à partir d'un fichier uploadé
     *
     * @param UploadedFile $uploadedFile
     * @return string
     *
     * @author Jordan DAVID <jdavid@habitat44.org>
     */
    public static function generateUniqFileName(UploadedFile $uploadedFile)
    {
        $extension = $uploadedFile->getClientOriginalExtension();
        if (!$extension) {
            $extension = 'bin';
        }
        return md5(uniqid()) . '.' . $extension;
    }

    /**
     * @param $str
     * @return string|string[]
     */
    public static function replaceAccents($str)
    {
        $search = explode(",", "ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,ø,Ø,Å,Á,À,Â,Ä,È,É,Ê,Ë,Í,Î,Ï,Ì,Ò,Ó,Ô,Ö,Ú,Ù,Û,Ü,Ÿ,Ç,Æ,Œ,°");

        $replace = explode(",", "c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,o,O,A,A,A,A,A,E,E,E,E,I,I,I,I,O,O,O,O,U,U,U,U,Y,C,AE,OE, ");

        return str_replace($search, $replace, $str);
    }

    /**
     * @param string $libelleLong
     * @return mixed|null|string|string[]
     * @author Adrian JOSSE-PAUPION
     * @throws \Exception
     */
    public static function generateLongToCourt(?string $name){
        if (is_null($name)){
            throw new \Exception('Le libellé ne doit pas être null');
        }
        //Génère le libelleCourt: remplace toutes les minuscules par des majuscules et les apostrophes et les espaces par des underscores
        $name = Utility::replaceAccents($name);
        $libelleCourt = mb_strtolower($name, 'UTF-8');
        $libelleCourt = preg_replace('/ /', '_', $libelleCourt);
        $libelleCourt = preg_replace('/\'/', '_', $libelleCourt);
        return $libelleCourt;
    }
}