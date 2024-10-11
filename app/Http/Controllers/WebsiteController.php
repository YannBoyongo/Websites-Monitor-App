<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function index(){
        // Fetch all websites from the database
        $websites = Website::orderBy('id', 'desc')->paginate(10);

        // Return the list of websites
        return view('websites.index', compact('websites'));
    }
    public function create(){
        // Return create website form
        return view('websites.create');
    }
    public function edit(Website $website){
        // Return edit website form
        return view('websites.edit', compact('website'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'url' => 'required|url',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ]);

        // Create a new website entry
        $website = Website::create([
            'url' => $request->url,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_up' => true, // By default, websites are enabled (up)
        ]);

        // Return the list of websites
        return redirect()->route('websites.index')->with('success', 'Website added successfully!');

    }

    public function update(Request $request, $id)
    {
        // Find the website by ID
        $website = Website::findOrFail($id);

        // Validate the incoming request
        $request->validate([
            //check if the url is valid and unique and in the database
            'url' => 'required|url|unique:websites,url,'. $website->id,
            'url' => 'required|url',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ]);

        // Update the website details
        $website->update([
            'url' => $request->url,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('websites.index')->with('success', 'Website updated successfully!');


    }

    public function toggleWebsiteStatus($id)
    {
        // Find the website by ID
        $website = Website::findOrFail($id);

        // Toggle the status (enable or disable)
        $website->is_up = !$website->is_up;
        $website->save();

        return response()->json([
            'message' => $website->is_up ? 'Website enabled successfully!' : 'Website disabled successfully!',
            'website' => $website
        ]);
    }

    public function checkWebsites(Request $request)
    {
        // Fetch all websites from the database
        $websites = Website::all();
        $downSites = [];

        // Extract the URLs to check
        $statuses = $this->checkWebsitesInParallel($websites->pluck('url')->toArray());

        // Loop through the results and check the status
        foreach ($websites as $website) {
            $status = $statuses[$website->url];
            
            if (!$status && $website->is_up) {
                // If the website is down, mark it as down in the database and add to the downSites array
                $website->update(['is_up' => false]);
                $downSites[] = $website->url;
            } elseif ($status && !$website->is_up) {
                // If the website is back up, mark it as up in the database
                $website->update(['is_up' => true]);
            }
        }

        // Return the list of down websites
        return response()->json([
            'down_sites' => $downSites,
            'message' => count($downSites) > 0 ? 'Some websites are down.' : 'All websites are up.'
        ]);
    }

    protected function checkWebsitesInParallel($urls)
    {
        $multiHandle = curl_multi_init();
        $curlHandles = [];

        // Create individual cURL handles for each URL
        foreach ($urls as $url) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);  // We just want to check if the site is up, so no body is needed
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);   // Set a reasonable timeout
            curl_multi_add_handle($multiHandle, $ch);
            $curlHandles[$url] = $ch;
        }

        // Execute all requests in parallel
        do {
            curl_multi_exec($multiHandle, $running);
            curl_multi_select($multiHandle);
        } while ($running > 0);

        // Collect the results
        $statuses = [];
        foreach ($curlHandles as $url => $ch) {
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $statuses[$url] = ($httpCode === 200);  // Site is up if we get a 200 HTTP status code
            curl_multi_remove_handle($multiHandle, $ch);
            curl_close($ch);
        }

        // Close the multi handle
        curl_multi_close($multiHandle);

        return $statuses;
    }
}
