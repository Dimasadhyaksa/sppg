<?php

$SUPABASE_URL = "https://anbtsmpmxhakfabamzgl.supabase.co";
$SUPABASE_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImFuYnRzbXBteGhha2ZhYmFtemdsIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjQwNTQ4OTcsImV4cCI6MjA3OTYzMDg5N30.YMYAxvvf2UatkUxiEHLmZgZH486KpO9UCHodgCkkM14";

function supabase_request($method, $endpoint, $body = null): mixed
{
    global $SUPABASE_URL, $SUPABASE_KEY;

    $url = rtrim(string: $SUPABASE_URL, characters: '/') . "/rest/v1/" . $endpoint;

    $headers = [
        "apikey: $SUPABASE_KEY",
        "Authorization: Bearer $SUPABASE_KEY",
        "Content-Type: application/json",
        "Accept: application/json",
        "Prefer: return=representation",
        "Content-Profile: public", // WAJIB!
    ];

    $ch = curl_init();

    curl_setopt(handle: $ch, option: CURLOPT_URL, value: $url);
    curl_setopt(handle: $ch, option: CURLOPT_RETURNTRANSFER, value: true);
    curl_setopt(handle: $ch, option: CURLOPT_HTTPHEADER, value: $headers);

    curl_setopt(handle: $ch, option: CURLOPT_SSL_VERIFYPEER, value: true);
    curl_setopt(handle: $ch, option: CURLOPT_CAINFO, value: "C:/Users/HP/php/cacert.pem");

    if ($method === "GET") {
        curl_setopt(handle: $ch, option: CURLOPT_HTTPGET, value: true);
    } elseif ($method === "POST") {
        curl_setopt(handle: $ch, option: CURLOPT_POST, value: true);
        curl_setopt(handle: $ch, option: CURLOPT_POSTFIELDS, value: json_encode($body));
    } elseif ($method === "PATCH") {
        curl_setopt(handle: $ch, option: CURLOPT_CUSTOMREQUEST, value: "PATCH");
        curl_setopt(handle: $ch, option: CURLOPT_POSTFIELDS, value: json_encode($body));
    } elseif ($method === "DELETE") {
        curl_setopt(handle: $ch, option: CURLOPT_CUSTOMREQUEST, value: "DELETE");
    }

    $response = curl_exec(handle: $ch);
    $err = curl_error(handle: $ch);
    curl_close(handle: $ch);

    if ($err) return ["error" => $err];

    return json_decode(json: $response, associative: true) ?? $response;
}

function supabase_get($endpoint): mixed
{
    return supabase_request(method: "GET", endpoint: $endpoint);
}

function supabase_insert($table, $data): mixed
{
    return supabase_request(method: "POST", endpoint: $table, body: $data);
}

function supabase_update($table, $id, $data)
{
    return supabase_request(
        method: "PATCH",
        endpoint: "$table?id=eq.$id",
        body: $data
    );
}

function supabase_delete($table, $id): mixed
{
    $id = '"' . $id . '"';
    return supabase_request(method: "DELETE", endpoint: "$table?id=eq.$id");
}
