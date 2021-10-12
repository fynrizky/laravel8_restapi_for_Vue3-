<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transaction = Transaction::orderBy('time', 'DESC')->get();
        $response = [
            'message' => 'List Transaction Order By Time',
            'data' => $transaction
        ];

        return response()->json($response, Response::HTTP_OK);
    }

 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //membuat validasi untuk inputan dari user
        $validator = Validator::make($request->all(),
        //syarat dlm menginput
        [
            'title' => ['required'],
            'amount' => ['required', 'numeric'],
            'type' => ['required', 'in:expense,revenue']
        ]);
        //jika validator gagal
        if($validator->fails()){
            //mengembalikan respon json validasi errornya, menggunakan response http 442
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        //jika berhasil

        try{
            //menyimpan response hasil query dari model transaksi dan 
            //metod create. yg requestnya dari semua data.
            $transaction = Transaction::create($request->all());
            //jika sukses kemudian membuat format respon
            $response =[
                'message'=>'Transaction created',
                'data' => $transaction
            ];
            //mengembalikan respon json, menggunakan respon http 201
            return response()->json($response, Response::HTTP_CREATED);

            //jika gagal
        }catch (QueryException $e){
            //mengembalikan respon json 
            return response()->json([
                'message'=> "Failed ".$e->errorInfo
            ]);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction=Transaction::findOrFail($id);
         //jika sukses kemudian membuat format respon
         $response =[
            'message'=>'Detail of Transaction',
            'data' => $transaction
        ];
        //mengembalikan respon json, menggunakan respon http 200
        return response()->json($response, Response::HTTP_OK);
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
        $transaction = Transaction::findOrFail($id); 
        //membuat validasi untuk inputan dari user
         $validator = Validator::make($request->all(),
         //syarat dlm menginput
         [
             'title' => ['required'],
             'amount' => ['required', 'numeric'],
             'type' => ['required', 'in:expense,revenue']
         ]);
         //jika validator gagal
         if($validator->fails()){
             //mengembalikan respon json validasi errornya, menggunakan response http 442
             return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
         }
         //jika berhasil
 
         try{
             //menyimpan response hasil query dari model transaksi dan 
             //metod create. yg requestnya dari semua data.
             $transaction->update($request->all());
             //jika sukses kemudian membuat format respon
             $response =[
                 'message'=>'Transaction updated',
                 'data' => $transaction
             ];
             //mengembalikan respon json, menggunakan respon http 200
             return response()->json($response, Response::HTTP_OK);
 
             //jika gagal
         }catch (QueryException $e){
             //mengembalikan respon json 
             return response()->json([
                 'message'=> "Failed ".$e->errorInfo
             ]);
         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id); 
       
         try{
             //menyimpan response hasil query dari model transaksi dan 
             //metod create. yg requestnya dari semua data.
             $transaction->delete();
             //jika sukses kemudian membuat format respon
             $response =[
                 'message'=>'Transaction deleted',
             ];
             //mengembalikan respon json, menggunakan respon http 200
             return response()->json($response, Response::HTTP_OK);
 
             //jika gagal
         }catch (QueryException $e){
             //mengembalikan respon json 
             return response()->json([
                 'message'=> "Failed ".$e->errorInfo
             ]);
         }
    }
}
