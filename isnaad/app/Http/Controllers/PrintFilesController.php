<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


//*********************************
// IMPORTANT NOTE
// ==============
// If your website requires user authentication, then
// THIS FILE MUST be set to ALLOW ANONYMOUS access!!!
//
//*********************************

//Includes WebClientPrint classes
include_once(app_path() . '/WebClientPrint/WebClientPrint.php');
use Neodynamic\SDK\Web\WebClientPrint;
use Neodynamic\SDK\Web\Utils;
use Neodynamic\SDK\Web\DefaultPrinter;
use Neodynamic\SDK\Web\InstalledPrinter;
use Neodynamic\SDK\Web\PrintFile;
use Neodynamic\SDK\Web\ClientPrintJob;

use Session;

class PrintFilesController extends Controller
{
    public function printMyFiles(Request $request){

        if ($request->exists(WebClientPrint::CLIENT_PRINT_JOB)) {

            //Create a ClientPrintJob obj that will be processed at the client side by the WCPP
            $cpj = new ClientPrintJob();
            //set client printer, for multiple files use DefaultPrinter...
            $cpj->clientPrinter = new DefaultPrinter();
            //set files-printers group by using special formatting!!!
            //Invoice.doc PRINT TO Printer1
            //DispatchForm.xls PRINT TO Printer2
            $cpj->printFileGroup = array(
                new PrintFile('https://ws.aramex.net/content/rpt_cache/93abbb03c4294bc9b8a1b237699e7b2d.pdf', 'Invoice_PRINT_TO_EPSONL565 (L565 Series) (Copy 1).pdf', null),
                new PrintFile('https://ws.aramex.net/content/rpt_cache/a50bad78f2184796ad56252647f7b21e.pdf', 'DispattchForm_PRINT_TO_EPSONL565 (L565 Series) (Copy 1).pdf', null),
                new PrintFile('https://aymakan.com.sa/pdf/generate/53a97569-2003-4021-a196-31e534824370', 'DispattchForm_PRINT_TO_EPSONL565 (L565 Series) (Copy 1).pdf', null)
              //  new PrintFile('https://ws.aramex.net/content/rpt_cache/93abbb03c4294bc9b8a1b237699e7b2d.pdf', 'DispattchForm_PRINT_TO_EPSONL565 (L565 Series) (Copy 1).pdf', null)
                );


            //Send ClientPrintJob back to the client
            return response($cpj->sendToClient())
                ->header('Content-Type', 'application/octet-stream');


        }
    }

}
