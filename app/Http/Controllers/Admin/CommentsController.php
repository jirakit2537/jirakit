<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Post;
use App\Comment;
use Session;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $title = 'จัดการข้อมูลผู้ใช้';
      $post = Post::all();
      $Comment = Comment::orderBy('id', 'DESC')->paginate(5);

      return view('comments.index', compact('post','title','Comment'));
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
    public function store(Request $request, $post_id)
    {
      $this->validate($request, [
          'name' => 'required',
          'email' => 'required' ,
          'comment' => 'required',

      ]);
        $post = Post::find($post_id);

        $comment = new Comment();
        $comment->name = $request->name;
        $comment->email = $request->email;
        $comment->comment = $request->comment;
        $comment->approved = true;
        $comment->post()->associate($post);
      //  $comment->user_id = '55';

        $comment->save();

        return redirect()->route('Post.show', [$post->slug])
            ->with('status','แสดงความคิดเห็นเรียบร้อย');

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
          $comment = Comment::find($id);
          return view('comments.edit')->withComment($comment);
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
          $comment = Comment::find($id);
          $this->validate($request, array('comment' => 'required'));
          $comment->comment = $request->comment;
          $comment->save();
          //Session::flash('success', 'Comment updated');
          return redirect()->route('comments.index')
              ->with('status','อัพเดทข้อมูลเรียบร้อย');
      }
      // public function delete($id)
      // {
      //     $comment = Comment::find($id);
      //     return view('comments.delete')->withComment($comment);
      // }
      /**
       * Remove the specified resource from storage.
       *
       * @param  int  $id
       * @return \Illuminate\Http\Response
       */
      public function destroy($id)
      {
          $comment = Comment::find($id);
          $post_id = $comment->post->id;
          $comment->delete();
      //    Session::flash('success', 'Deleted Comment');
          return back()->with('status','ลบข้อความเรียบร้อย');
      }
}
