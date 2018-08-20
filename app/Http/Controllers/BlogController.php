<?php

namespace App\Http\Controllers;

use App\Blog;
use App\BlogCategory;
use App\BlogComment;
use App\Http\Requests;
use App\Http\Requests\BlogCommentRequest;
use App\Http\Requests\BlogRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Response;
use Sentinel;
use App\Helpers\Template;
use View;
use Carbon\Carbon;

class BlogController extends WeedController
{
    private $tags;

    public function __construct()
    {
        parent::__construct();
        $this->tags = Blog::allTags();
    }


    public function getIndexFrontend()
    {
        // Grab all the blogs
        $limit = (int)Template::getSetting('listview_blog_limit');
        
        $blogs = Blog::getActiveBlogs()->latest()->paginate($limit);
        
        $tags = $this->tags;
        // Show the page
        return View('frontend/blog/blogs', compact('blogs', 'tags'));
    }


    public function getBlogFrontend($slug = '')
    {
        if ($slug == '') {
            $blog = Blog::first();
        }
        
        try {
            $blog = Blog::findBySlugOrIdOrFail($slug);

            if(!$blog->isActive()) {
                return redirect('blog');
            }
            
            $blog->increment('views');
        } catch (ModelNotFoundException $e) {
            return Response::view('404', array(), 404);
        }

        // Show the page
        return View::make('frontend/blog/item', compact('blog'));

    }

    public function getBlogTagFrontend($tag)
    {
        $limit = (int)Template::getSetting('listview_blog_limit');
         
        $blogs = Blog::getActiveBlogs()->withAnyTags($tag)->paginate($limit);
            
        $tags = $this->tags;
        
        return View('frontend/blog/blogs', compact('blogs', 'tags'));
    }

    public function storeCommentFrontend(BlogCommentRequest $request, Blog $blog)
    {
        $blogcooment = new BlogComment($request->all());
        $blogcooment->blog_id = $blog->id;
        $blogcooment->save();

        return redirect('blogitem/' . $blog->slug);
    }

    public function index()
    {
        // Grab all the blogs
        $blogs = Blog::all();
        // Show the page
        return View('admin.blog.index', compact('blogs'));
    }

    public function create()
    {
        $blogcategory = BlogCategory::lists('title', 'id');
        return view('admin.blog.create', compact('blogcategory'));
    }

    public function store(BlogRequest $request)
    {
        $request = Template::replaceDates($request);
        
        $blog = new Blog($request->except('image', 'tags'));
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $folderName = Template::getBlogImageDir();
            $picture = str_random(10) . '.' . $extension;
            $blog->image = $picture;
        }
        $blog->user_id = Sentinel::getUser()->id;
        $blog->save();

        if ($request->hasFile('image')) {
            $destinationPath = public_path() . $folderName;
            $request->file('image')->move($destinationPath, $picture);
        }

        $blog->tag($request->tags);

        if ($blog->id) {
            return redirect('admin/blog')->with('success', trans('blog/message.success.create'));
        } else {
            return redirect('admin/blog')->withInput()->with('error', trans('blog/message.error.create'));
        }

    }

    public function show(Blog $blog)
    {
        $comments = BlogComment::all();
        return view('admin.blog.show', compact('blog', 'comments', 'tags'));
    }


    public function edit(Blog $blog)
    {
        $blogcategory = BlogCategory::lists('title', 'id');
        return view('admin.blog.edit', compact('blog', 'blogcategory'));
    }


    public function update(BlogRequest $request, Blog $blog)
    {
        $request = Template::replaceDates($request);
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $folderName = Template::getBlogImageDir();
            $picture = str_random(10) . '.' . $extension;
            $blog->image = $picture;
        }

        if ($request->hasFile('image')) {
            $destinationPath = public_path() . $folderName;
            $request->file('image')->move($destinationPath, $picture);
        }
        
        $blog->retag($request['tags']);

        if ($blog->update($request->except('image', '_method', 'tags'))) {
            return redirect('admin/blog')->with('success', trans('blog/message.success.update'));
        } else {
            return redirect('admin/blog')->withInput()->with('error', trans('blog/message.error.update'));
        }
    }

    public function getModalDelete(Blog $blog)
    {
        $model = 'blog';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('delete/blog', ['id' => $blog->id]);
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('blog/message.error.delete', compact('id'));
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    public function destroy(Blog $blog)
    {

        if ($blog->delete()) {
            return redirect('admin/blog')->with('success', trans('blog/message.success.delete'));
        } else {
            return redirect('admin/blog')->withInput()->with('error', trans('blog/message.error.delete'));
        }
    }


    public function storecomment(BlogCommentRequest $request, Blog $blog)
    {
        $comments_enabled = Template::enableBlogComments($blog->category->id);
        if($comments_enabled) {
            $blogcooment = new BlogComment($request->all());
            $blogcooment->blog_id = $blog->id;
            $blogcooment->save();
        }
        return redirect('admin/blog/' . $blog->id . '/show');
    }
    
    public function getCategoryList($slug = '')
    {
        if ($slug == '') {
            $blog_category = BlogCategory::first();
        }
        
        $limit = (int)Template::getSetting('listview_blog_limit');
        
        try {
            $blog_category = BlogCategory::findBySlugOrIdOrFail($slug);
            
            $blogs = Blog::getActiveBlogs()->where('blog_category_id', '=', $blog_category->id)->latest()->paginate($limit);
            
        } catch (ModelNotFoundException $e) {
            return Response::view('404', array(), 404);
        }

        return View::make('frontend/blog/blogs', compact('blogs', 'blog_category'));
    }
}
