<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Html2Text\Html2Text;


class ToolController extends Controller
{
    //
    public function index()
    {
        return view('tool.index');
    }
    public function CalculateAndGetDensity(Request $request) {
        if ($request->isMethod('POST')) {
            $rawhtml = null;
            if (isset($request->textInput)) {
                $rawhtml = $request->textInput;
            } elseif (isset($request->urlInput)) {
                $rawhtml = file_get_contents($request->urlInput);                
            }

            if (!empty($rawhtml)) {
                $html = new Html2Text($rawhtml);
                $text = strtolower($html->getText());
                $totalWordCount = str_word_count($text); // Get the total count of words in the text string
                $wordsAndOccurrence  = array_count_values(str_word_count($text, 1)); // Get each word and the occurrence count as key value array
                arsort($wordsAndOccurrence); // Sort into descending order of the array value (occurrence)

                $keywordDensityArray = [];
                // Build the array
                foreach ($wordsAndOccurrence as $key => $value) {
                    $keywordDensityArray[] = ["keyword" => $key, // keyword
                        "count" => $value, // word occurrences
                        "density" => round(($value / $totalWordCount) * 100,2)]; // Round density to two decimal places.
                }

                return $keywordDensityArray;
            }
        }
    }
}
