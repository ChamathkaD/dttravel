<?php

namespace App\Http\Controllers;

class DebugController extends Controller
{
    public function index()
    {
        $plainText = '1024|99';
        $publicKey = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC0ZwV3z+lrxrA5DnlGX1Hw1L/+
5MvrcOqq/ELEUviQdgL8U8c3OynBBPA6U390aPU0LCldu2zcDVT1jI6yYfmh/nk5
ZeEpM4C8q7QHMjgs6Jt1U2iBx2R/N8c6dpaKfhkgxB6SRRpvZ9yiv0ZvDKbirM+L
7xThASka0eZRzK3D0QIDAQAB
-----END PUBLIC KEY-----';

        openssl_public_encrypt($plainText, $encrypt, $publicKey);

        $payment = base64_encode($encrypt);

        dd($payment);
    }
}
