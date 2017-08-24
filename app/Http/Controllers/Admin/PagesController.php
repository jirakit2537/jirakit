<?php

namespace App\Http\Controllers\Admin;

use App\Page;
use Illuminate\Http\Request;
use Baum\MoveNotPossibleException;
use App\Http\Controllers\Controller;

class PagesController extends Controller
{
  protected $pages;

    public function __construct(Page $pages)
    {
        $this->pages = $pages;

      //  parent::__construct($pages);
    }

    public function index(){
$title = 'จัดการข้อมูลผู้ใช้';
          $pages = Page::all();
        //  $pages = Page::getNestedList('title');
        // $pages = Page::orderBy('parent_id', '=', 0)->get();
    //    $pages = $pages->count() > 1 ? $pages->toHierarchy() : $pages->get();
    return view('pages.index', compact('pages','title'));


    }



    public function create(Page $page){

        $title = 'จัดการข้อมูลผู้ใช้';
        $templates = $this->getPageTemplates();
        $orderPages = Page::all();
        // Page::orderBy('lft', 'asc')->get();
      //  $orderPages1 = Page::pluck('Title', 'id')->toArray();

        //  $orderPages = $orderPages->pluck('title', 'id');
        return view('pages.create', compact('title','orderPages','page','templates'));
    }


    public function store(Request $request)
    {


        $this->validate($request, [
            'title' => 'required',
            'uri' => 'required|unique:pages,uri' ,
            'name' => 'required',
            'icon' => 'required',
            'content' => 'required',
        ]);
        //
        $page = $this->pages->create($request->only('title', 'uri', 'name', 'icon', 'content','template', 'hidden'));

             $this->updatePageOrder($page, $request);

        // $pages = new Page();
        // $pages->title = $request->input('title');
        // $pages->uri = $request->input('uri');
        // $pages->name = $request->input('name');
        // $pages->content = $request->input('content');
      //   $input = $request->all();
      // // $input['parent_id'] = empty($input['parent_id']) ? 0 : $input['parent_id'];
    //   $pages->save();
      //   //  $pages->updateOrder($pages, $request);
      //
      //     Page::create($input);
      //   $this->updateOrder($input, $request);

      //    $pages->orderPage = $request->input('orderPage');
        //
        // $orderPage = $request->findOrFail($orderPage);
        //        if ($order == 'before') {
        //            $request->moveToLeftOf($orderPage);
        //        } elseif ($order == 'after') {
        //            $request->moveToRightOf($orderPage);
        //        } elseif ($order == 'childOf') {
        //            $request->makeChildOf($orderPage);
        //        }


        //
        // $pages->updatePageOrder($pages, $request);
        //Page::find($id)->update($request->all());


        return redirect()->route('pages.index')
            ->with('success','บันทึกข้อมูลเรียบร้อย');
    }


    public function edit($id){
        $title = 'จัดการข้อมูลผู้ใช้';
         $templates = $this->getPageTemplates();
        // $orderPages = Page::orderBy('lft', 'asc')->get();
        // $pages = Page::find($id);
        $pages = $this->pages->findOrFail($id);

          //$templates = $this->getPageTemplates();
          $orderPages = $this->pages->orderBy('lft', 'asc')->get();
            //$orderPages = $this->pages->where('hidden', false)->orderBy('lft', 'asc')->get();

            //return view('backend.pages.form', compact('page', 'templates', 'orderPages'));




        return view('pages.edit', compact('pages','title','orderPages','templates'));
    }


    public function update(Request $request, $id)
    {
        //'uri' => 'required|unique:pages,uri,'.$user->id, ป้องกันค่าซ้ำยกเว้น id นั้นๆ
        $user = Page::find($id);

        $this->validate($request, [
            'title' => 'required',
            'uri' => 'required|unique:pages,uri,'.$user->id,
            'name' => 'required',
            'icon' => 'required',
            'content' => 'required',
        ]);

        $page = $this->pages->findOrFail($id);

               if ($response = $this->updatePageOrder($page, $request)) {
                   return $response;
               }

               $page->fill($request->only('title', 'uri', 'name', 'icon', 'content', 'template', 'hidden'))->update();





        // $input = $request->all();
        // Page::find($id)->update($request->all());
        //
        // $orderPage = $input->findOrFail($orderPage);
        //        if ($order == 'before') {
        //            $input->moveToLeftOf($orderPage);
        //        } elseif ($order == 'after') {
        //            $input->moveToRightOf($orderPage);
        //        } elseif ($order == 'childOf') {
        //            $input->makeChildOf($orderPage);
        //        }

        return redirect()->route('pages.index')
            ->with('success','อัพเดทข้อมูลเรียบร้อย');
    }

   // public function show($slug) {

    //    $pages = Page::where('uri', '=',$slug)->first();
    //    $title = 'feses';
    //    return view('pages.show', compact('pages','title'));
  //  }

    public function destroy($id)
    {
    //  Page::find($id)->delete();

    $page = $this->pages->findOrFail($id);

        foreach ($page->children as $child) {
            $child->makeRoot();
        }

        $page->delete();
      return redirect()->route('pages.index')
          ->with('success', 'ลบข้อมูลเรียบร้อย');
    }

    protected function getPageTemplates()
   {
       $templates = config('cms.templates');

       return ['' => ''] + array_combine(array_keys($templates), array_keys($templates));
   }

    protected function updatePageOrder(Page $page, Request $request)
    {
        if ($request->has('order', 'orderPage')) {
            try {
                $page->updateOrder($request->input('order'), $request->input('orderPage'));
            } catch (MoveNotPossibleException $e) {
                return redirect(route('pages.edit', $page->id))->withInput()->withErrors([
                    'error' => 'Cannot make a page a child of itself.'
                ]);
            }
        }
    }

    public function trash(){

            $title = 'ข้อมูลที่ถูกลบ';
          $pages = Page::onlyTrashed()->get();
        //  $pages = Page::getNestedList('title');
        // $pages = Page::orderBy('parent_id', '=', 0)->get();
    //    $pages = $pages->count() > 1 ? $pages->toHierarchy() : $pages->get();


        return view('pages.trash', compact('pages','title'));
    }
    public function restore($id){


          $pages = Page::onlyTrashed()->find($id);
          $pages->restore();
        //  $pages = Page::getNestedList('title');
        // $pages = Page::orderBy('parent_id', '=', 0)->get();
    //    $pages = $pages->count() > 1 ? $pages->toHierarchy() : $pages->get();


    return redirect()->route('pages.trash')
        ->with('success','กู้คืนข้อมูลเรียบร้อย');
    }

    public function deletever($id){


          $pages = Page::onlyTrashed()->find($id);
          $pages->forceDelete();
        //  $pages = Page::getNestedList('title');
        // $pages = Page::orderBy('parent_id', '=', 0)->get();
    //    $pages = $pages->count() > 1 ? $pages->toHierarchy() : $pages->get();


    return redirect()->route('pages.trash')
        ->with('success','ลบข้อมูลถาวรเรียบร้อย');
    }





}
