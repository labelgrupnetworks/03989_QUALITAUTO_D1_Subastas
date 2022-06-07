<?php

namespace App\Http\Controllers\admin;

use Request;
use Controller;
use Config;
use Illuminate\Support\Facades\DB;


use App\Models\Blog;
use App\Models\Category;
use App\Models\CategorysBlog;

class BlogController extends Controller
{
    public function __construct() {
        $this->blog = new Blog();
        $this->categorysBlog = new CategorysBlog();
        $this->category = new Category();
        $this->lang = Config::get('app.locales');
    }
    public function index($id = null)
    {

        $data = array();
        $sub_categ = array();
        $sec = array();
        $categorys = $this->category->getCategSubCateg(false,'0');
        foreach($categorys as $categ){
            $sub_categ[$categ->cod_sec] = ucfirst(mb_strtolower($categ->des_sec));
            $sec[$categ->lin_ortsec1] = $categ->des_ortsec0;
        }

        $this->blog->lang = 'ES';
        $categorys=$this->blog->getCategorysLang();
        $all_categories = array();
        foreach($categorys as $categ){
            $all_categories[$categ->url_category_blog_lang] = $categ;
        }
        asort($all_categories);
        asort($sec);
        asort($sub_categ);
         $data= array( 'sub_categ' => $sub_categ, 'sec' => $sec, 'idiomes' =>$this->lang,'categories'=>$all_categories );
        if(!empty($id)){
            $this->blog->idblog = $id;
            foreach( $this->lang as $key_alng => $lang){
                $this->blog->lang = strtoupper($key_alng);
                $inf_noticia['lang'][strtoupper($key_alng)] = head($this->blog->getNoticiaLang());
                if(!empty($inf_noticia['lang'][strtoupper($key_alng)]->lot_categories_web_blog)){
                   $inf_noticia['lot_categories_web_blog'] = explode(",",$inf_noticia['lang'][strtoupper($key_alng)]->lot_categories_web_blog);
                }
                 if(!empty($inf_noticia['lang'][strtoupper($key_alng)]->lot_sub_categories_web_blog)){
                   $inf_noticia['lot_sub_categories_web_blog'] = explode(",",$inf_noticia['lang'][strtoupper($key_alng)]->lot_sub_categories_web_blog);
                }
            }
            $categ_blog = $this->blog->getNoticiaRelCategory();
            foreach($categ_blog as $value){
                $inf_noticia['categories'][] = $value->idcat_web_blog_rel_category;
            }
            $data['noticia'] = $inf_noticia;
		}

        return \View::make('admin::pages.editBlog',array('data' => $data));
    }


    public function getBlogs(){
        $data = array();
        $data = $this->blog->getAllBlogs();
        return \View::make('admin::pages.blog',array('data' => $data));
    }

    public function getCategoryBlog(){
        $categorys = array();
        $categorys = $this->categorysBlog->getCategorys();
        return \View::make('admin::pages.categoryBlog',array('data' => $categorys));
    }

    public function seeCategoryBlog($id = null){
        $categorys = array();
        if(!empty($id)){
            $this->blog->id =$id;
            $categorys_temp = $this->blog->getCategorysLang();
            foreach($categorys_temp as $categ){
                $categorys[$categ->lang_category_blog_lang] = $categ;
            }

        }

        $data['categorys'] = $categorys;
        $data['idiomes'] = $this->lang;
        return \View::make('admin::pages.editCategoryBlog',array('data' => $data));

    }



    public function EditBlogCategory(){
        $this->categorysBlog->id=Request::input('id');
        $this->categorysBlog->title=Request::input('title');
        $this->categorysBlog->orden=Request::input('orden');
        if(!empty(Request::input('orden'))){
            $this->categorysBlog->orden = Request::input('orden');
        }else{
            $this->categorysBlog->orden = Request::input('id');
        }
        if(empty(Request::input('enabled'))){
            $this->categorysBlog->enabled=0;
        }else{
            $this->categorysBlog->enabled=1;
        }
        if($this->categorysBlog->id==0){

            $max_id = $this->categorysBlog->Max_Category_Blog();
            $this->categorysBlog->id= $max_id+1;
            $this->categorysBlog->orden = $max_id+1;

            /*
             * Si falla la inserción de cualquier idioma, realiza rollback de las operaciones anteriores,
             * no comitea hasta finalizar todas las operaciones
             */
            DB::transaction(function () {

                $this->categorysBlog->InsertCategoryBlog();
                foreach ($this->lang as $lang => $idiom) {
                    $lang = strtoupper($lang);
                    $this->categorysBlog->lang = $lang;
                    $this->categorysBlog->name_category = trim(Request::input('name_' . $lang));
                    $this->categorysBlog->title_category = trim(Request::input('title_' . $lang));
                    $this->categorysBlog->url_category = trim(Request::input('url_' . $lang));
                    $this->categorysBlog->metatit_category = trim(Request::input('meta_title_' . $lang));
                    $this->categorysBlog->metades_category = trim(Request::input('meta_desc_' . $lang));
                    $this->categorysBlog->metacont_category = trim(Request::input('meta_cont_' . $lang));
                    $this->categorysBlog->InsertCategoryBlogLang();
                }

            });
        }else{
            $this->categorysBlog->UpdateCategoryBlog();
            foreach($this->lang as $lang => $idiom){
               $lang = strtoupper($lang);
               $this->categorysBlog->lang = $lang;
               $this->categorysBlog->name_category=trim(Request::input('name_'.$lang));
               $this->categorysBlog->title_category=trim(Request::input('title_'.$lang));
               $this->categorysBlog->url_category=trim(Request::input('url_'.$lang));
               $this->categorysBlog->metatit_category=trim(Request::input('meta_title_'.$lang));
               $this->categorysBlog->metades_category=trim(Request::input('meta_desc_'.$lang));
               $this->categorysBlog->metacont_category=trim(Request::input('meta_cont_'.$lang));
               $this->categorysBlog->UpdateCategoryBlogLang();
            }
        }

        return $this->categorysBlog->id;
    }

    public function EditBlog(){
        $this->blog->id=Request::input('id');
        $this->blog->title=Request::input('title');
        $this->blog->categories_web = '';
        $this->blog->sub_categories_web = '';
        $this->blog->img = Request::input('file_url');
        $this->blog->date = Request::input('date');
		$this->blog->category_principal = Request::input('categ_blog_principal');
		$this->blog->author_web_blog = trim(Request::input('author'));

        if(!empty(Request::input('sub_categ'))){
            foreach(Request::input('sub_categ') as $sub_categ){
                $this->blog->sub_categories_web .= $sub_categ.',';
            }
            $this->blog->sub_categories_web = trim(substr($this->blog->sub_categories_web, 0, -1));
        }

        if(!empty(Request::input('sec'))){
            foreach(Request::input('sec') as $sec_categ){
                $this->blog->categories_web .= $sec_categ.',';
            }
            $this->blog->categories_web = trim(substr($this->blog->categories_web, 0, -1));
        }




        if($this->blog->id == 0){
            $max_id = $this->blog->MaxBlog();
            $this->blog->idblog_lang = $max_id + 1;
            $this->blog->InsertBlog();
            $this->blog->id = $this->blog->idblog_lang;
            foreach($this->lang as $lang => $idiom){
               $this->blog->id_lang = $this->blog->MaxBlogLang();
               $this->blog->id_lang ++;
               $lang = strtoupper($lang);
               $this->blog->lang = $lang;
               $this->blog->title_blog=trim(Request::input('title_'.$lang));
               $this->blog->cita_blog=trim(Request::input('cita_'.$lang));
               $this->blog->url_blog=str_slug(trim(Request::input('url_'.$lang), '-'));
               $this->blog->metatit_blog=trim(Request::input('meta_title_'.$lang));
               $this->blog->metades_blog=trim(Request::input('meta_desc_'.$lang));
               $this->blog->video_blog = trim(Request::input('video_'.$lang));
               $this->blog->cont_blog=trim(Request::input('cont_'.$lang));

                if(empty(Request::input('enabled_'.$lang))){
                    $this->blog->enabled_blog=0;
                }else{
                    $this->blog->enabled_blog=1;
                }
               $this->blog->InsertBlogLang();
            }
        }else{
            $this->blog->idblog_lang = $this->blog->id;
            $this->blog->UpdateBlog();

            foreach($this->lang as $lang => $idiom){
               $lang = strtoupper($lang);
               $this->blog->lang = $lang;
               $this->blog->title_blog=trim(Request::input('title_'.$lang));
               $this->blog->cita_blog=trim(Request::input('cita_'.$lang));
               $this->blog->url_blog=str_slug(trim(Request::input('url_'.$lang), '-'));
               $this->blog->metatit_blog=trim(Request::input('meta_title_'.$lang));
               $this->blog->metades_blog=trim(Request::input('meta_desc_'.$lang));
               $this->blog->video_blog = trim(Request::input('video_'.$lang));
               $this->blog->cont_blog=trim(Request::input('cont_'.$lang));
                if(empty(Request::input('enabled_'.$lang))){
                    $this->blog->enabled_blog=0;
                }else{
                    $this->blog->enabled_blog=1;
                }
               $this->blog->UpdateBlogLang();
            }
        }

        $this->blog->DeleteRelBlog();

        if(!empty(Request::input('cate_blog'))){
            $categ_blog = Request::input('cate_blog');
            foreach($categ_blog as $cate_blog){
                $this->blog->rel_category = $cate_blog;
                $this->blog->InsertRelBlog();
            }
        }

        return $this->blog->id ;

    }



}
