<?php


namespace App\Model;


class TypeFile
{
    const MAX_SIZE = '200Mi';
    const TYPE_FILE_TRACK = 1;
    const TYPE_FILE_CLIP = 2;
    const TYPE_FILE_PHOTO_ARTICLE = 3;
    const TYPE_FILE_PHOTO_ARTISTE = 4;
    const MIME_TYPE_IMAGE = 'image/*';
    const MIME_TYPE = 'creation/*, audio/vnd.wav, audio/mp3, audio/x-wav';

    const ALL_TYPE_FILE = [
        self::TYPE_FILE_TRACK,
        self::EXTENSION_VIDEO,
        self::EXTENSIONS_IMAGES
    ];

    //Extensions
    const EXTENSIONS_IMAGES = ['jpg', 'JPG', 'JPEG', 'jpeg', 'png', 'PNG'];
    const EXTENSIONS_IMAGES_ETENDUE = ['jpg', 'JPG', 'JPEG', 'jpeg', 'png', 'PNG', 'gif', 'tiff'];
    const EXTENSIONS_WORD = ['doc', 'docx'];
    const EXTENSIONS_EXCEL = ['xls', 'xlsx'];
    const EXTENSIONS_PDF = ['pdf', 'PDF'];
    const EXTENSION_AUDIO = ['audio/mpeg', 'audio/flac', 'audio/vnd.wav', 'audio/mp3', 'audio/x-wav'];
    const EXTENSION_VIDEO = ['video/mp4', 'video/ogg'];

    const MIME_FILE_TRACK = 'creation/*';
    const MIME_FILE_CLIP= 'clip/*';
}