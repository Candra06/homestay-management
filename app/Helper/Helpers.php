<?php
namespace App\Helper;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Google\Client;
use Illuminate\Support\Facades\Http;
use App\Models\LogRequest;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Spatie\Browsershot\Browsershot;

class Helpers {

    public static function tanggal($tanggal, $isTimestamps = false)
    {
        if (!$tanggal) {
            return "";
        }
        if ($isTimestamps) $carbonDate = Carbon::parse($tanggal);
        else $carbonDate = Carbon::createFromFormat("Y-m-d", $tanggal);
        $tanggalFormat = $carbonDate->translatedFormat('j F Y');
        return $tanggalFormat;
    }

    public static function tanggalTime($tanggal)
    {
        $carbonDate = Carbon::parse($tanggal);
        $date = $carbonDate->translatedFormat('j F Y, H:i') . " WIB";
        return $date;
    }

    public static function tanggalFormat($tanggal, $format = 'Y-m-d')
    {
        $carbonDate = Carbon::parse($tanggal);
        $tanggalFormat = $carbonDate->translatedFormat($format);
        return $tanggalFormat;
    }

    public static function tanggalTimeFormatted($tanggal)
    {
        $carbonDate = Carbon::parse($tanggal);
        $tanggalFormat = $carbonDate->translatedFormat('d/m/Y, H:i');
        return $tanggalFormat;
    }

    public static function tableColumns($data = [], $order = [], $search = [])
    {
        $format = [];
        foreach ($data as $value) {
            if ($value == "action") {
                $format[] = ["data" => $value, "name" => $value, "searchable" => false, "orderable" => false, "width" => '10%'];
            } else if ($value == "produk"){
                $format[] = ["data" => $value, "name" => $value, "searchable" => true, "orderable" => true, "width" => '15%'];
            } else if ($value == "price"){
                $format[] = ["data" => $value, "name" => $value, "searchable" => true, "orderable" => true, "width" => '15%'];
            } else if ($value == "supplier"){
                $format[] = ["data" => $value, "name" => $value, "searchable" => true, "orderable" => true, "width" => '15%'];
            }else {
                if ($value == "DT_RowIndex" || $value == "select") {
                    $format[] = ["data" => $value, "name" => $value, "width" => '7%'];
                } else {
                    $format[] = ["data" => $value, "name" => $value,];
                }
            }
        }
        foreach ($order as $key => $val) {
            $format[$key]['orderable'] = $val;
        }
        foreach ($search as $val) {
            $format[$key]['searchable'] =  $val;
        }
        return $format;
    }

    public static function sendEmail($email, $message) {

    }

    public static function sendWhatsapp($phone, $message) {
        try {
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => config('services.url_whatsapp'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                // CURLOPT_HTTPHEADER => array(
                //     'Content-Type: application/json',
                //     'x-app-key: ' . config('services.app_key'),
                //     'x-auth-key: ' . config('services.auth_key'),
                // ),
                CURLOPT_POSTFIELDS =>
                    [
                        'appkey'   => config('services.app_key'),
                        'authkey'   => config('services.auth_key'),
                        'to'   => $phone,
                        'message' => $message,
                    ],
            ));

            $response = curl_exec($curl);

            return $response;
            curl_close($curl);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public static function generateQrDevice() {
        try {
            $curl = curl_init();
            // return config('services.device_whatsapp_id');
            $postFields = [
                'iduser' => config('services.user_whatsapp_id'),
                'device' => config('services.device_whatsapp_id')
            ];

            curl_setopt_array($curl, [
                CURLOPT_URL => config('services.url_whatsapp')."/qr/",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $postFields,
            ]);

            $response = curl_exec($curl);

            curl_close($curl);
            return $response;
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public static function sendFcm($message) {
        $file = base_path() . '/config.json';
        $projectId = 'sukalelang-id';
        $serviceAccountPath = $file;
        $accessToken = Helpers::getAccessToken($serviceAccountPath);
        $url = 'https://fcm.googleapis.com/v1/projects/' . $projectId . '/messages:send';
        $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['message' => $message]));
        $response = curl_exec($ch);
        if ($response === false) {

            throw new Exception('Curl error: ' . curl_error($ch));
            return false;
        }
        curl_close($ch);
        return true;
    }

    public static function getAccessToken($serviceAccountPath) {
        $client = new Client();
        $client->setAuthConfig($serviceAccountPath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->useApplicationDefaultCredentials();
        $token = $client->fetchAccessTokenWithAssertion();
        return $token['access_token'];
     }


    public static function fixphone($phone, $code = '62', $default = NULL)
    {
        $phone  = trim($phone);
        $phone  = str_replace(" ", "", $phone);
        $phone  = str_replace("-", "", $phone);

        if(empty($phone)){
            return $default;
        }

        if(substr($phone, 0, 1) == '+'){
            return $phone;
        }

        $code   = str_replace('+', '', $code);
        $phone  = preg_replace("/[\s-]+/", "", $phone);
        $phone  = preg_replace("/^(?:\+?$code|0)?/", "+{$code}", $phone);

        return $phone;
    }

    public static function rupiah($angka, $currency = "$ ")
    {
        $hasil_rupiah = $currency . number_format($angka, 0, ',', '.');
        return $hasil_rupiah;
    }

    public static function getNumberOnly($string)
    {
        return preg_replace('/[^0-9]/', '', $string);
    }

    public static function generateStatus($status) {
        switch ($status) {
            case 'Waiting Payment':
                return '<span class="status-tag bg-warning">'.$status.'</span>';
                break;
            case 'Paid':
                return '<span class="status-tag bg-success">'.$status.'</span>';
                break;
            case 'No Bid':
                return '<span class="status-tag bg-secondary">'.$status.'</span>';
                break;
            case 'BnR':
                return '<span class="status-tag bg-danger">'.$status.'</span>';
                break;
            default:
                return '<span class="status-tag bg-warning">'.$status.'</span>';
                break;
        }
    }

    public static function generateStatusPayment($status) {
        switch ($status) {
            case 'Verifying':
                return '<span class="status-tag bg-warning">'.$status.'</span>';
                break;
            case 'Done':
                return '<span class="status-tag bg-success">'.$status.'</span>';
                break;
            case 'Waiting':
                return '<span class="status-tag bg-secondary">'.$status.'</span>';
                break;
            case 'Expired':
                return '<span class="status-tag bg-danger">'.$status.'</span>';
                break;
            case 'Packing':
                return '<span class="status-tag bg-info">'.$status.'</span>';
                break;
            default:
                return '<span class="status-tag bg-secondary">'.$status.'</span>';
                break;
        }
    }

    public static function generatePlatform($platform) {
        switch ($platform) {
            case 'Instagram':
                return '<span class="instagram-tag">'.$platform.'</span>';
                break;
            case 'Website':
                return '<span class="web-tag">'.$platform.'</span>';
                break;
            default:
                return '<span class="web-tag">'.$platform.'</span>';
                break;
        }
    }

    public static function generateRandomString($length = 8) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public static function mengantarGet($url, $param) {
        $url =  config('services.url_mengantar').$url;
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['message' => $message]));
        // $response = curl_exec($ch);
        $response = Http::get($url, $param);
        $data = $response->json();
        return json_decode($response,true);
    }

    public static function mengantarPost($url, $body) {
        $type = '';
        switch ($url) {
            case 'order':
                $type = 'Create Order';
                break;
            case 'time':
                $type = 'Create Time';
                break;
            default:
                $type = 'Shipping Fee';
                break;
        }
        $url =  config('services.url_mengantar').$url;
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['message' => $message]));
        // $response = curl_exec($ch);
        $response = Http::post($url, $body);
        $data = $response->json();
        LogRequest::create([
                'type' =>$type,
                'url' => $url,
                'request' => json_encode($body, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR),
                'response' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR),
            ]);
        return json_decode($response,true);
    }

    public static function printResi($data = []) {
        return ['message' =>'No available printer connected '.PHP_OS_FAMILY, 'success'=>false];
        try {
            $printers = [];
            $selected ="";
            if (PHP_OS_FAMILY === 'Windows') {
                exec('wmic printer get Name,ShareName /format:table', $output);

                foreach ($output as $index => $line) {
                    // Skip empty lines and the header row
                    if (trim($line) === '' || $index === 0) continue;

                    // Match columns using multiple spaces as delimiter
                    $parts = preg_split('/\s{2,}/', trim($line));

                    if (count($parts) === 2) {
                        $printers[] = [
                            'name' => $parts[0],
                            'sharename' => $parts[1],
                        ];
                    }
                }
            }else{
                exec('lpstat -p', $output);
                // return $output;
                return ['message' =>$output, 'success'=>false];
                foreach ($output as $line) {
                    if (preg_match('/printer\s+(\S+)/', $line, $matches)) {
                        $printers[] = [
                            'name' => $matches[1],
                            'sharename' => '',
                        ];
                    }
                }
            }
            if (count($printers) > 0) {
                $printer = $printers[0]['sharename'];
            } else {
                return ['message' =>'No available printer connected '.PHP_OS_FAMILY, 'success'=>false];
            }

            $connector = new WindowsPrintConnector($printer);
            $printer = new Printer($connector);

            for ($i=0; $i < count($data); $i++) {
                $printer->initialize();
                $printer->text("------------------------------------------------\n");
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setTextSize(3, 3);
                $printer->text("No. Resi \n".$data[$i]['resi']."\n");
                $printer->setTextSize(1,1);
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                // $printer->text("Date: " . now()->format('Y-m-d H:i:s') . "\n");
                $printer->text("------------------------------------------------\n");
                $printer->selectPrintMode(Printer::FONT_B);

                $printer->text("Pengirim\n");
                $printer->setEmphasis(true); // Enable bold
                $printer->text("Sukalelang\n");
                $printer->setEmphasis(false);
                self::printTwoColumnReceipt($printer, "\nPerumahan alam hijau blok F1-34 \nBotosari,dukuh mencek\nKecamatan sukorambi \nKabupaten jember\n", "",40,1);
                $printer->text("Penerima\n");
                $printer->setEmphasis(true); // Enable bold
                $printer->text(self::hideString($data[$i]['reciever_name'])."\n");
                $printer->setEmphasis(false);
                self::printTwoColumnReceipt($printer, "\n".self::maskPhoneNumbersInText($data[$i]['receiver_address']), "",40,1);
                self::printTwoColumnReceipt($printer, "\n".self::hideString($data[$i]['receiver_phone']), "",40,1);

                $printer->feed(1);
                self::printTwoColumnReceipt($printer, "Item", "Qty",45,10);
                $printer->text("------------------------------------------------\n");
                foreach ($data[$i]['items'] as $item) {
                    self::printTwoColumnReceipt($printer,  $item['name'], $item['qty'], 45, 10);

                }
                $printer->selectPrintMode();

                $printer->setJustification(Printer::JUSTIFY_CENTER);

                $printer->cut(Printer::CUT_FULL);
                $printer->close();
            }
            return ['message' => 'Success', 'success'=>true];
        } catch (\Throwable $th) {
            return ['message' => $th->getMessage(), 'success'=>false];
        }
    }

    public static function printTwoColumnReceipt(Printer $printer, $leftText, $rightText, $totalWidth = 80, $rightWidth = 50) {
        $leftWidth = $totalWidth - $rightWidth;

        // Wrap left text
        $leftLines = explode("\n", wordwrap($leftText, $leftWidth, "\n", true));
        $rightLines = explode("\n", wordwrap($rightText, $rightWidth, "\n", true));

        $maxLines = max(count($leftLines), count($rightLines));

        for ($i = 0; $i < $maxLines; $i++) {
            $left = $leftLines[$i] ?? '';
            $right = $rightLines[$i] ?? '';

            $line = str_pad($left, $leftWidth);

            // Only right-align first line
            if ($i == 0) {
                $line .= str_pad($right, $rightWidth, ' ', STR_PAD_LEFT);
            }

            $printer->text($line . "\n");
        }
    }

    public static function hideString($string, $start = 2, $end = 2, $maskChar = '*') {
        $length = strlen($string);
        if ($length <= ($start + $end)) return $string;
        $maskedLength = $length - $start - $end;
        return substr($string, 0, $start) . str_repeat($maskChar, $maskedLength) . substr($string, -$end);
    }

    public static function maskPhoneNumbersInText($text) {
        return preg_replace_callback('/(\b08\d{8,10})\b/', function ($matches) {
            $phone = $matches[1];
            $length = strlen($phone);
            if ($length <= 4) return $phone;
            return substr($phone, 0, 2) . str_repeat('*', $length - 4) . substr($phone, -2);
        }, $text);
    }

    public static function numberOnly(string $s): int {
        // buang semua kecuali digit dan minus, lalu cast
        return (int) preg_replace('/[^\d\-]/', '', $s);
    }
}
?>
