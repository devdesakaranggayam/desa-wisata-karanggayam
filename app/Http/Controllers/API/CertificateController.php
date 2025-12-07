<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Sertifikat;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Laravel\Facades\Image;

class CertificateController extends Controller
{
    private function getImage($sertifikat) 
    {
        $user = $sertifikat->user;
        $img = Image::read(public_path('img/sertifikat.png'));
        $geist = 'fonts/geist.ttf';
        $text = $user->nama;
        $tanggal = tgl_indo($sertifikat->tanggal);
        // AUTO CENTER HORIZONTAL
        $x = $img->width() / 2;
        $y = 900; // bebas kamu tentukan posisi vertikalnya

        $img->text($text, $x, $y, function ($font) {
            $font->filename(public_path('fonts/dancing.ttf')); 
            $font->size(100);
            $font->color('186752');
            $font->align('center'); 
            $font->valign('middle');
        });

        // $text2 = "Sebagai peserta dalam Eksplorasi GeoPark Desa Karanggayam. Kegiatan tersebut bertujuan";
        // $text3 = "memperkenalkan dan mengidentifikasi potensi geologi, hayati, dan budaya. Kegiatan dilaksanakan";
        // $text4 = 'pada tanggal: '. $tanggal .' di Desa Karanggayam, Kabupaten Kebumen, Jawa Tengah.';

        // $img->text($text2, $x, 1075, function ($font) use ($geist) {
        //     $font->filename(public_path($geist)); 
        //     $font->size(45);
        //     $font->color('#717680');
        //     $font->align('center'); 
        //     $font->valign('middle');
        // });

        // $img->text($text3, $x, 1150, function ($font) use ($geist) {
        //     $font->filename(public_path($geist)); 
        //     $font->size(45);
        //     $font->color('#717680');
        //     $font->align('center'); 
        //     $font->valign('middle');
        // });

        // $img->text($text4, $x, 1225, function ($font) use ($geist) {
        //     $font->filename(public_path($geist)); 
        //     $font->size(45);
        //     $font->color('#717680');
        //     $font->align('center'); 
        //     $font->valign('middle');
        // });

        return $img->encodeByExtension('png');
    }
    
    public function index(Request $request)
    {
        $user = auth('api')->user();

        $data = Sertifikat::where('user_id', $user->id)->paginate(10);
        return ApiResponse::paginated($data, 'OK');

    }

    public function generate(Sertifikat $sertifikat)
    {
        $user = auth('api')->user();

        if ($user->id != $sertifikat->user_id) {
            return response()->json(["success" => false], 404);
        }

        $encoded = $this->getImage($sertifikat);
        return response($encoded->toString(), 200)
            ->header('Content-Type', $encoded->mediaType());
    }
}
