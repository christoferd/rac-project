<?php

namespace App\Http\Controllers;

use App\Library\SessionAlert;
use App\Models\Client;
use App\Models\MergeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function index()
    {
        if(userCan('access clients')) {
            return view('client.index');
        }
        return view('app.unauthorized');
    }

    public function show(int $id)
    {
        if(userCan('access clients')) {
            return view('client.show', ['clientId' => $id]);
        }
        return view('app.unauthorized');
    }

    /*
     * Step 1 - Select records to merge
     * GET
     */
    public function merge(Request $request)
    {
        // Order
        $orderBy = 'clients.'.$request->get('orderBy', 'name');
        $fields = ['name', 'address', 'phone_number', 'rating', 'notes', 'count_rentals'];
        if(!\in_array($orderBy, $fields)) {
            $orderBy = 'clients.name';
        }

        return view('client.merge_form_select_records');
    }

    /*
     * Step 2 - Set the data to keep
     * POST
     */
    public function mergeEdit()
    {
        // Either get the Posted clients, or retrieve from session, in case they come back from edit
        $clients = request()->post('clients', session()->get('merge_clients', []));

        // Redirect to Step #1 on fail
        if(empty($clients)) {
            \messageError(__t('Required', 'Select', 'Clients'));
            return \redirect()->route('merge-clients');
        }

        // Store in case we come back
        session()->put('merge_clients', $clients);

        $fields = ['name', 'address', 'phone_number', 'rating', 'notes'];
        $records = [];
        foreach($clients as $clientId) {
            $records[] = Client::findOrFail($clientId)->toArray();
        }

        return view('client.merge_form_final_edit_data',
                    \compact('fields', 'records'));
    }

    /**
     * Step 3 - Run merge process.
     * POST
     */
    public function mergeRun(Request $request)
    {
        // Validate
        $fields = ['name', 'address', 'phone_number', 'rating', 'notes'];
        $input = $request->only($fields);
        $messages = [];

        $rules = [
            'name'         => 'required|string|max:80',
            'address'      => 'string|max:100',
            'phone_number' => 'string|max:20',
            'notes'        => 'string|max:300',
            'rating'       => 'integer|min:-1|max:10',
        ];

        $validator = Validator::make($input, $rules, [], $input);

        if($validator->fails()) {
            return redirect(route('merge-clients-edit'))
                ->withErrors($validator)
                ->withInput($input);
        }

        $mergedClientsArr = session()->get('merge_clients');
        if(empty($mergedClientsArr)) {
            throw new \Exception('Programming issue: Clients list should not be empty here. '.__CLASS__.'::'.__FUNCTION__);
        }

        try {
            // &$messages Pass by reference otherwise the changes are not kept outside the closure!
            DB::transaction(function() use ($input, $mergedClientsArr, &$messages) {

                // Create New Record with input data
                $client = new Client();
                $client->fill($input);
                $client->save();

                // Alert
                $messages[] = __t('Created', 'New Client');
                $messages[] = linkTo(route('clients.show', [$client->id]), __t('View', 'Client'));

                // - Merge Log
                MergeLog::add([
                                  'message'           => 'Created new Client',
                                  'merged_client_ids' => $mergedClientsArr, // Array
                                  'new_client_data'   => $client->toArray() // Array
                              ]);

                // Remove other records
                foreach($mergedClientsArr as $mergedClientId) {
                    // Load Client
                    $mergedClient = Client::find($mergedClientId);
                    // Check
                    if(!is_null($mergedClient)) {

                        /*
                         * Rentals attached
                         */
                        foreach($mergedClient->rentals as $rental) {
                            // Move to new record
                            $rental->client_id = $client->id;
                            $rental->save();

                            // Merge Log
                            MergeLog::add([
                                              'message'            => 'Moved Rental to new Client',
                                              'rental_id'          => $rental->id,
                                              'client_id'          => $client->id,
                                              'previous_client_id' => $mergedClient->id
                                          ]);

                            // Message
                            $messages[] = sprintf('%s ( %s )', __t('Reallocated', 'the Rental'), $rental->detailsString());
                        }

                        /*
                         * Images attached
                         */
                        foreach($mergedClient->images as $clientImage) {
                            // Move to new record
                            $clientImage->client_id = $client->id;
                            $clientImage->save();

                            // Merge Log
                            MergeLog::add([
                                              'message'            => 'Moved ClientImage to new Client',
                                              'rental_id'          => $clientImage->id,
                                              'client_id'          => $client->id,
                                              'previous_client_id' => $mergedClient->id
                                          ]);

                            // Message
                            $messages[] = sprintf('%s - %s', __t('Reallocated', 'the image'), $clientImage->detailsString());
                        }

                        /*
                         * Remove Client
                         */
                        $mergedClient = $mergedClient->fresh();
                        $mergedClient->delete();

                        // Merge Log
                        MergeLog::add([
                                          'message'     => 'Deleted Client',
                                          'client_id'   => $mergedClient->id,
                                          'client_data' => $mergedClient->toArray() // Array
                                      ]);

                        // Message
                        $messages[] = (\sprintf('%s: %s',
                                                __t('Deleted', 'the Client'), $mergedClient->detailsString()));
                    }
                    else {
                        throw new \Exception(__('Unable to delete the record.').' ID #'.$mergedClientId);
                    }
                }
            });
        }
        catch(\Throwable $ex) {
            SessionAlert::error($ex->getMessage());
            return \redirect(route('merge-clients-edit'));
        }

        // Complete
        session()->forget('merge_clients');
        $messages[] = __('Process Complete');
        \messageSuccess(__t('Merged', 'Clients'));
        return \redirect()->route('merge-complete')
                          ->with('messages', $messages);
    }

    public function mergeComplete()
    {
        $messages = session()->get('messages');
        $messages = implode("\n\n", $messages);
        return view('client.merge_complete', \compact('messages'));
    }

}
