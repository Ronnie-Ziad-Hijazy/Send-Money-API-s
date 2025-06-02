<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;

class MailController extends Controller
{
    //
    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        $this->mail->isSMTP();
        $this->mail->Host = env('MAIL_HOST');
        $this->mail->SMTPAuth = true;
        $this->mail->Username = env('MAIL_USERNAME');
        $this->mail->Password = env('MAIL_PASSWORD');
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // <-- important
        $this->mail->Port = 587; // <-- TLS port
        $this->mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME', 'Mailer'));
    }

    /**
     * Contact us
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function contactUs(Request $request)
    {
        // configure an SMTP
        $validated = $this->validate($request, [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255',
            'phone_no' => 'required|string',
            'message' => 'required|string',
        ]);
        $this->mail->addAddress(env('MAIL_SEND_TO'), 'Arcella Support');
        $this->mail->Subject = 'Money Sent Notify';
        $this->mail->isHTML(true);
        $this->mail->Body = '<html>Dear Admin, <br><br>A new contact form message has been received, here are the details:<br><b>Name:</b> ' . $validated["name"] . '<br><b>Phone:</b> ' . $validated["phone_no"] . '<br><b>Email: </b>' . $validated["email"] . '<br><b>Message:</b><br> ' . $validated["message"] . '</html>';
        if (!$this->mail->send()) {
            return response([
                'error_msg' => "An Error Occurred while sending your message",
                'mailer_error' => $this->mail->ErrorInfo

            ], 400);
        } else {
            return response(["success" => true]);
        }

    }
}
