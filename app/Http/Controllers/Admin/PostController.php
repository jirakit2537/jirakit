<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Post;
use App\Tag;
use App\Comment;
use Auth;
use App\Category;
use Image;
use Storage;
use File;
use Carbon\Carbon;
use App\Http\Requests\PostStore;

class PostController extends Controller
{


  /**
  * Display a listing of the resource.
  *
  * @return Response
  */
 public function index()
 {
   $title = 'จัดการข้อมูลผู้ใช้';
   $category = Category::all();
   $Posts = Post::orderBy('id', 'DESC')->paginate(5);
   return view('Post.index', compact('Posts','title','category'));

 }

 /**
  * Show the form for creating a new resource.
  *
  * @return Response
  */
 public function create()
 {

  $tags = Tag::pluck('tag', 'id');
   $category = Category::pluck('name', 'id');
  //$tags = Tag::all();
  //$title = 'จัดการข้อมูลผู้ใช้';
   return view('Post.create', compact('tags','category'));
 }

 /**
  * Store a newly created resource in storage.
  *
  * @return Response
  */
 public function store(PostStore $request, $user_id = null)
 {
  //dd($request);
  //  $this->validate($request, [
  //      'title' => 'required',
  //      'slug' => 'required|unique:posts,slug' ,
  //      'summary' => 'required',
  //      'content' => 'required',
  //      'category_id' => 'required',
  //      'featured_image' => 'required|mimes:jpg,jpeg,png|max:2500',
   //
  //  ]);

   $Posts = new Post();
   $Posts->title = $request->input('title');
   $Posts->slug = $request->input('slug');
   $Posts->summary = $request->input('summary');
   $Posts->content = $request->input('content');
    $Posts->category_id = $request->input('category_id');
    $Posts->active = isset($request['active']);
    $Posts->showc = isset($request['showc']);

   if (Auth::user()->id ) {
            $Posts->user_id = Auth::user()->id;
        }

        //บันทึกรูป
if($request->hasFile('featured_image')) {
  $image = $request->file('featured_image');
  $filename = time() . '.' . $image->getClientOriginalExtension();
  $location = public_path('images/post/' . $filename);
  $locaresize = public_path('images/resize/' . $filename);
  // Image::make($image)->resize(700, 400)->save($location);
  Image::make($image)->resize(50, 50)->save($locaresize);
  // $test = Image::make($image->getRealPath());
  $test = Image::make($image->getRealPath())->resize(1280, 720, function ($constraint) {
    $constraint->aspectRatio();
    // $constraint->upsize();
});
  $watermark = Image::make('Frame.png')->insert($test, 'center')->save($location);
  $Posts->image = $filename;
}

   $Posts->save();
//   $Posts->tags()->sync($request->tags, false);
   if (isset($request->tags)) {
     $Posts->tags()->sync($request->tags);
     //$Posts->Category()->sync($request->category);
   }else {
     $Posts->tags()->sync(array());
  //   $Posts->Category()->sync(array());
   }


// $request->session()->flash('status', 'บนัทกึข้อมลูเรียบร้อยแล้ว');
//  return back();
  $request->session()->flash('status', 'บันทึกข้อมูลเรียบร้อยแล้ว');
   return redirect()->route('Post.index');
      // ->with('success','Pages created successfully');
 }

 /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return Response
  */
 // public function show($slug)
 // {
 //   $tags = Tag::all();
 //   $category = Category::all();
 //   $comments = Comment::all();
 //  //   $commentCT = Comment::where('approved', true)->count();
 //    //  $commentCF = Comment::where('approved', false)->count();
 //   $Post = Post::where('slug', '=',$slug)->first();
 //   $title = $Post->title;
 //   return view('Post.show', compact('Post','title','tags','category','comments'));
 // }

 /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return Response
  */
 public function edit($id)
 {
   //$tags = Tag::all();
   $title = 'จัดการข้อมูลผู้ใช้';
   $Posts = Post::find($id);
   $tags = Tag::pluck('tag', 'id');
   $Category = Category::pluck('name', 'id');
   $PostTag = $Posts->tags->pluck('id', 'id')->toArray();
  $Postcategory = $Posts->Category->pluck('id', 'id')->toArray();
   return view('Post.edit', compact('Posts','title','tags','PostTag','Postcategory','Category'));
 }

 /**
  * Update the specified resource in storage.
  *
  * @param  int  $id
  * @return Response
  */
 public function update(Request $request, $id)
 {
   $user = Post::find($id);

   $this->validate($request, [
       'title' => 'required',
       'slug' => 'required|unique:posts,slug,'.$user->id,
       'summary' => 'required',
       'content' => 'required',
'featured_image' => 'mimes:jpg,jpeg,png|max:2500',
   ]);
  $Posts = Post::find($id);
$request['active'] = isset($request['active']) ? 1 : 0;
$request['showc'] = isset($request['showc']) ? 1 : 0;

if($request->hasFile('featured_image')) {
  $image = $request->file('featured_image');
  $filename = time() . '.' . $image->getClientOriginalExtension();
  $location = public_path('images/post/' . $filename);
  $locaresize = public_path('images/resize/' . $filename);
  // Image::make($image)->resize(700, 400)->save($location);
  // $test = Image::make($image->getRealPath());
  $test = Image::make($image->getRealPath())->resize(1280, 720, function ($constraint) {
    $constraint->aspectRatio();
    $constraint->upsize();
});
  $watermark = Image::make('Frame.png')->insert($test, 'center')->save($location);
    Image::make($image)->resize(50, 50)->save($locaresize);
  $oldFilename = $Posts->image;

  $Posts->image = $filename;

  Storage::delete($oldFilename);
  File::delete(public_path() . '\\images\post\\' . $oldFilename);
 File::delete(public_path() . '\\images\resize\\' . $oldFilename);
}


    $Posts->update($request->all());

   if (isset($request->tags)) {
     $Posts->tags()->sync($request->tags);
  //   $Posts->Category()->sync($request->Category);
   }else {
     $Posts->tags()->sync(array());
  //   $Posts->Category()->sync(array());
   }

   return redirect()->route('Post.index')
       ->with('status','อัพเดทข้อมูลเรียบร้อยแล้ว');
 }









 /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return Response
  */
 public function destroy($id)
 {
   $post = Post::find($id);
   $post->tags()->detach();
   //$post->Category()->detach();
   $oldFilename = $post->image;
    if ($oldFilename != 'testssd.jpg')


     {            Storage::delete($oldFilename);
         File::delete(public_path() . '\\images\post\\' . $oldFilename);
        File::delete(public_path() . '\\images\resize\\' . $oldFilename);
                     }
   Post::destroy($id);

   return redirect()->route('Post.index')
       ->with('status', 'ลบข้อมูลเรียบร้อยแล้ว');
 }
}
