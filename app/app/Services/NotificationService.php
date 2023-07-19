<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function sendNotification($user, $amount, $transaction)
    {
        Log::debug("Iniciando o serviço de notificação: \n user_id: $user->id \n transaction_id: $transaction");
        
        $notificationServiceUrl = 'http://o4d9z.mocklab.io/notify';
        $client = new Client();
    
        // Make external service request
        $response = $client->post($notificationServiceUrl, [
            'form_params' => [
                'user_id' => $user->id,
                'message' => 'Você recebeu um pagamento de R$ ' . $amount,
            ],
        ]);
    

        if ($response->getStatusCode() === 200) {
            Log::debug('Notificação de transação enviada com sucesso!');
        } else {
            Log::error('Ocorreu um erro ao enviar a notificação!');
        }
    }
}
