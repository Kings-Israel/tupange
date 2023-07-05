<?php

namespace App\Http\Controllers;

use App\Models\RaisedIssue;
use App\Models\RaisedIssueResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResolutionCenterController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      $issues = RaisedIssue::with(['raisedIssueResponses' => function($query) {
         $query->orderBy('created_at', 'DESC');
      }])->orderBy('created_at', 'DESC')->get()->take(10);

      return view('resolution-center', compact('issues'));
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {
      //
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
      $this->validate($request, [
         'issue' => ['required']
      ]);

      $phoneNumberCheck = preg_match('/(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?/', preg_replace('/\s+/', '', $request->issue));
      $emailCheck = preg_match('/(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/', preg_replace('/\s+/', '', $request->issue));

      if($phoneNumberCheck != false) {
         return back()->withInput()->with('error', 'Do not share phone numbers.');
      }
      if($emailCheck != false) {
         return back()->withInput()->with('error', 'Do not share email addresses.');
      }

      RaisedIssue::create([
         'user_id' => auth()->user()->id,
         'issue' => $request->issue,
      ]);

      $redirectUrl = redirect()->back()->with('success', 'Issue recoreded successfully')->getTargetUrl();

      return $request->wantsJson() ? new JsonResponse(['redirectPath' => $redirectUrl], 200) : redirect()->back()->with('success', 'Issue recoreded successfully');
   }

   /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function show($id)
   {
      //
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function edit($id)
   {
      //
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, $id)
   {
      $this->validate($request, [
         'issue' => ['required']
      ]);

      $phoneNumberCheck = preg_match('/(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?/', preg_replace('/\s+/', '', $request->issue));
      $emailCheck = preg_match('/(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/', preg_replace('/\s+/', '', $request->issue));

      if($phoneNumberCheck != false) {
         return back()->withInput()->with('error', 'Do not share phone numbers.');
      }
      if($emailCheck != false) {
         return back()->withInput()->with('error', 'Do not share email addresses.');
      }

      $issue = RaisedIssue::find($id);
      $issue->update([
         'issue' => $request->issue
      ]);

      $redirectUrl = redirect()->back()->with('success', 'Issue Updated successfully')->getTargetUrl();

      return $request->wantsJson() ? new JsonResponse(['redirectPath' => $redirectUrl], 200) : redirect()->back()->with('success', 'Issue Updated successfully');
   }

   public function issueResponse(Request $request)
   {
      $this->validate($request, [
         'issue_response' => ['required']
      ]);

      $phoneNumberCheck = preg_match('/(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?/', preg_replace('/\s+/', '', $request->issue_response));
      $emailCheck = preg_match('/(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/', preg_replace('/\s+/', '', $request->issue_response));
      if($phoneNumberCheck != false) {
         return back()->withInput()->with('error', 'Do not share phone numbers.');
      }
      if($emailCheck != false) {
         return back()->withInput()->with('error', 'Do not share email addresses.');
      }

      RaisedIssueResponse::create([
         'user_id' => auth()->user()->id,
         'raised_issue_id' => $request->issue_id,
         'response' => $request->issue_response,
      ]);

      $redirectUrl = redirect()->back()->with('success', 'Response recoreded successfully')->getTargetUrl();

      return $request->wantsJson() ? new JsonResponse(['redirectPath' => $redirectUrl], 200) : redirect()->back()->with('success', 'Response recorded successfully');
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($id)
   {
      //
   }
}
