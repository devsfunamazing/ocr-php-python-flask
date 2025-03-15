<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PythonIntegrationController extends Controller
{
    //
    public function index(){
        return view('welcome');
    }

    public function convertPdf(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240'
        ]);

        $file = $request->file('file');
        $client = new Client();

        try {
            $response = $client->post('127.0.0.1:3000/convert-json', [
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => fopen($file->getPathname(), 'r'),
                        'filename' => $file->getClientOriginalName(),
                    ]
                ]
            ]);

            // Vérifier si la réponse est une erreur JSON
            if ($response->getStatusCode() !== 200) {
                return response()->json([
                    'error' => json_decode($response->getBody(), true) ?? "Erreur inconnue"
                ], $response->getStatusCode());
            }
            $body = json_decode($response->getBody(), true);
            return response()->json($body);
            // return response($response->getBody(), 200, [
            //     'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            //     'Content-Disposition' => 'attachment; filename="converted.xlsx"',
            // ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function convertPdfs(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:pdf|max:10240' // Accepter plusieurs fichiers PDF
        ]);

        $files = $request->file('files');
        $client = new Client();

        try {
            $multipart = [];
            foreach ($files as $file) {
                $multipart[] = [
                    'name'     => 'files',
                    'contents' => fopen($file->getPathname(), 'r'),
                    'filename' => $file->getClientOriginalName(),
                ];
            }

            $response = $client->post('', [
                'multipart' => $multipart
            ]);

            // Vérifier si la réponse est une erreur JSON
            if ($response->getStatusCode() !== 200) {
                return response()->json([
                    'error' => json_decode($response->getBody(), true) ?? "Erreur inconnue"
                ], $response->getStatusCode());
            }

            $body = json_decode($response->getBody(), true);
            return response()->json($body);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
