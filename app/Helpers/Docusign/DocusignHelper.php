<?php

namespace App\Helpers\Docusign;

use App\Enums\RevenuePlanType;
use App\Enums\TitleType;
use App\Helpers\FileUploadHelper;
use App\Models\Title;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class DocusignHelper extends DocusignBase {


    private function revenueType(int $revenueType) {
        return match ($revenueType) {
            RevenuePlanType::Avod->value => 'Avod',
            RevenuePlanType::AvodBuyer->value => 'Avod_Buy_Up',
            default => 'Pay_Per_View',
        };
    }

    public function getFormUrlData($titleId): object|null {
        $title = Title::findOrFail($titleId);
        if (!$title) {
            return null;
        }
        $revenuePlan = $title->getRevenuePlan;
        if (!$revenuePlan) {
            return null;
        }

        $duration = $title->getMovieMeta->duration ?? 'N/A';
        $territories = $title->getLicenceCountry()->pluck('name')->implode(', ') ?? "";
        $termStartDate = Carbon::now()->format('m/d/Y');
        $termEndDate = Carbon::now()->addYear(2)->format('m/d/Y');

        $getRevenueType = $this->revenueType($revenuePlan->type);
        $formId = env('DOCUSIGN_FORM_ID');
        if (!$formId) {
            return null;
        }

        $webFormBaseUrl = env('DOCUSIGN_WEB_FORM_URL'); // https://apps.docusign.com
        $webFormInstanceUrl = $webFormBaseUrl . '/api/webforms/v1.1/accounts/' . $this->accountId . '/forms/' . $formId . '/instances';

        $tokenObj = $this->getToken();
        $client = new Client();
        $response = $client->request('POST', $webFormInstanceUrl, [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => $tokenObj->token_type . ' ' . $tokenObj->access_token,
            ],
            'body' => json_encode([
                'clientUserId' => (string)$title->uuid,
                'formValues' => [
                    'Signer_email' => auth()->user()->email,
                    'Title_Length' => (string)$duration,
                    'Title_License_Territories' => $territories,
                    'Term_Start_Date' => $termStartDate,
                    'Term_End_Date' => $termEndDate,
                    'Title_Name' => $title->name,
                    'Title_Type' => TitleType::from($title->type->value)->displayName(),
                    'Revenue_Type' => $getRevenueType,
                ]
            ])
        ]);
//        external web url
//        $url = $formData['formUrl'] ?? null;
//        $instanceToken = $formData['instanceToken'] ?? null;
//        $url . '#instanceToken=' . $instanceToken

        return json_decode($response->getBody()->getContents());
    }

    private function modifyUUid($uuid) {
        if (strlen($uuid) === 36) {
            $first = substr($uuid, 0, 8) ?? '';
            $second = substr($uuid, 9, 4) ?? '';
            $third = substr($uuid, 14, 4) ?? '';
            $fourth = substr($uuid, 19, 4) ?? '';
            $fifth = substr($uuid, 24, 12) ?? '';
            $uuid = $second . $third . $fifth . $first . $fourth;
        }
        return $uuid;
    }

    public function getEnvelopeDocuments($envelopeId): object {

        if (!$envelopeId) {
            return (object)[];
        }

        // Saved Default to temp directory
        $filePath = 'agreements';
        $fileName = "agreement_" . $this->modifyUUid($this->accountId) . "_" . $this->modifyUUid($envelopeId) . '.pdf';

        $documentsList = [];
        $isExist = FileUploadHelper::filePathUrl($fileName, $filePath);

        if ($isExist) {
            $documentsList[] = $isExist;
            return (object)$documentsList;
        }

        $tokenObj = $this->getToken();
        $documentId = '1'; // default is 1
        $webDocumentUrl = env('DOCUSIGN_WEB_DOCUMENT_URL') . '/restapi/v2.1/accounts/' . $this->accountId . '/envelopes/' . $envelopeId . '/documents/' . $documentId;

        $client = new Client();
        $response = $client->request('GET', $webDocumentUrl, [
            'headers' => [
                'Content-Type' => 'application/pdf',
                'Content-Transfer-Encoding' => 'base64',
                'Authorization' => $tokenObj->token_type . ' ' . $tokenObj->access_token,
            ]
        ]);

        $fullPath = $filePath . '/' . $fileName;
        Storage::put($fullPath, base64_decode($response->getBody()->getContents()));

        $documentsList[] = FileUploadHelper::filePathUrl($fileName, $filePath);
        // return should be always multiple document
        // currently we are only getting primary document.
        return (object)$documentsList;
    }

}
