<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Web_Content_Page extends Model
{
	protected $table = 'web_content_page';
	protected $primaryKey = 'id_content_page';

	public $timestamps = false;
	public $incrementing = true;
	//public  $keyType = string;
	//permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];

	//contantes para el tipo table_rel_content_page
	const TABLE_REL_CONTENT_PAGE_BLOG = 'WEB_BLOG_LANG';
	const TABLE_REL_CONTENT_PAGE_PAGE = 'WEB_PAGE';

	//constante para el type_content_page
	const TYPE_CONTENT_PAGE_HTML = 'HTML';
	const TYPE_CONTENT_PAGE_TEXT = 'TEXT';
	const TYPE_CONTENT_PAGE_IMAGE = 'IMAGE';
	const TYPE_CONTENT_PAGE_VIDEO = 'VIDEO';
	/* const TYPE_CONTENT_PAGE_FILE = 'FILE'; */
	const TYPE_CONTENT_PAGE_BANNER = 'BANNER';
	const TYPE_CONTENT_PAGE_IFRAME = 'IFRAME';
	const TYPE_CONTENT_PAGE_YOUTUBE = 'YOUTUBE';

	public static function getConstantsTypesContentPages()
	{
		return [
			self::TYPE_CONTENT_PAGE_HTML,
			self::TYPE_CONTENT_PAGE_TEXT,
			self::TYPE_CONTENT_PAGE_IMAGE,
			self::TYPE_CONTENT_PAGE_VIDEO,
			/* self::TYPE_CONTENT_PAGE_FILE, */
			self::TYPE_CONTENT_PAGE_BANNER,
			self::TYPE_CONTENT_PAGE_IFRAME,
			self::TYPE_CONTENT_PAGE_YOUTUBE,
		];
	}

	public static function getTypeContentPagesName($type_content_page)
	{
		$names = [
			self::TYPE_CONTENT_PAGE_HTML => 'HTML',
			self::TYPE_CONTENT_PAGE_TEXT => 'TEXTO',
			self::TYPE_CONTENT_PAGE_IMAGE => 'IMAGEN',
			self::TYPE_CONTENT_PAGE_VIDEO => 'VIDEO',
			/* self::TYPE_CONTENT_PAGE_FILE => 'ARCHIVO', */
			self::TYPE_CONTENT_PAGE_BANNER => 'BANNER',
			self::TYPE_CONTENT_PAGE_IFRAME => 'IFRAME',
			self::TYPE_CONTENT_PAGE_YOUTUBE => 'YOUTUBE',

		];

		return $names[$type_content_page];
	}

	//metodo para obtener nombre de la columna de la tabla con la que tiene relacion
	public static function getTableRelContentPages($table_rel_content_page)
	{
		switch ($table_rel_content_page) {
			case self::TABLE_REL_CONTENT_PAGE_BLOG:
				return 'id_web_blog_lang';
				break;
			case self::TABLE_REL_CONTENT_PAGE_PAGE:
				return 'id_web_page';
				break;
			default:
				return 'id_web_blog_lang';
				break;
		}
	}

	//scope para obtener el contenido segun el campo table_rel_content_page que es el nombre de la tabla con la que tiene relacion
	public function scopeJoinRelation($query, $table_rel_content_page)
	{
		$nameTableRel = $this->getTableRelContentPages($table_rel_content_page);
		return $query->join($table_rel_content_page, $nameTableRel, '=', 'rel_id_content_page');
	}

	//scope para where según tipo de relacion
	public function scopeWhereCustomRelation($query, $table_rel_content_page, $rel_id_content_page)
	{
		return $query->where('table_rel_content_page', $table_rel_content_page)
			->when(is_array($rel_id_content_page), function ($query) use ($rel_id_content_page) {
				return $query->whereIn('rel_id_content_page', $rel_id_content_page);
			}, function ($query) use ($rel_id_content_page) {
				return $query->where('rel_id_content_page', $rel_id_content_page);
			});
		/* return $query->where([
			['table_rel_content_page', '=', $table_rel_content_page],
			['rel_id_content_page', '=', $rel_id_content_page]
		]); */
	}

	public function scopeResourcesRelation($query)
	{
		return $query->whereIn('type_content_page', array_diff(self::getConstantsTypesContentPages(), [self::TYPE_CONTENT_PAGE_BANNER, self::TYPE_CONTENT_PAGE_HTML]))
			->with('contentResource');
	}

	public function scopeBlogLangRelation($query)
	{
		return $query->where('table_rel_content_page', self::TABLE_REL_CONTENT_PAGE_BLOG)
			->with('webBlogLang');
	}

	public function contentHtml()
	{
		return $this->hasOne(Web_Content_Html::class, 'id_content', 'type_id_content_page');
	}

	public function contentResource()
	{
		/**mirar donde poner el where */
		return $this->hasOne(Web_Content_Resource::class, 'id_content', 'type_id_content_page');
	}

	public function webBlogLang()
	{
		return $this->hasOne(Web_Blog_Lang::class, 'id_web_blog_lang', 'rel_id_content_page');
	}

	public function getContentAttribute()
	{
		$type = $this->type_content_page;

		if(empty($type)) {
			return null;
		}

		if(in_array($type, [self::TYPE_CONTENT_PAGE_HTML, self::TYPE_CONTENT_PAGE_TEXT])) {
			if(empty($this->contentHtml)) {
				return null;
			}
			return $this->contentHtml->html_content;
		}

		if(in_array($type, [self::TYPE_CONTENT_PAGE_IMAGE, self::TYPE_CONTENT_PAGE_VIDEO, self::TYPE_CONTENT_PAGE_IFRAME, self::TYPE_CONTENT_PAGE_YOUTUBE])) {
			return $this->contentResource->url_content ?? null;
		}

		//pendiente
		if($type === self::TYPE_CONTENT_PAGE_BANNER) {
			return $this->contentBanner->banner_content ?? null;
		}

		return null;
	}

	public function getPublicPathAttribute()
	{
		$relationType = $this->table_rel_content_page;
		$fileSystem = $relationType == self::TABLE_REL_CONTENT_PAGE_BLOG ? 'blog' : 'page';

		$publicPath = config("filesystems.disks.{$fileSystem}.root");
		$relativePath = $this->relativePath;

		return $publicPath . '/' . $relativePath;
	}

	public function getRelativePathAttribute()
	{
		$relationType = $this->table_rel_content_page;
		$relativePath = $relationType == self::TABLE_REL_CONTENT_PAGE_BLOG
			? $this->webBlogLang->idblog_web_blog_lang ?? null
			: $this->rel_id_content_page;

		return $relativePath;
	}

	public function deleteMediaResource()
	{
		$fileSystem = $this->table_rel_content_page == Web_Content_Page::TABLE_REL_CONTENT_PAGE_BLOG ? 'blog' : 'page';
		$storageDisk = Storage::disk($fileSystem);

		$oldMedia = $this->contentResource->url_content ?? null;
		if ($oldMedia) {
			$oldMedia = str_replace($storageDisk->url(''), '', $oldMedia);
			$storageDisk->delete($oldMedia);
		}

		$this->contentResource()->delete();
	}

	public function upsertMediaResouce($file)
	{
		$contentResource = $this->contentResource()->firstOrCreate([
			'id_content' => $this->type_id_content_page
		]);

		$fileSystem = $this->table_rel_content_page == Web_Content_Page::TABLE_REL_CONTENT_PAGE_BLOG ? 'blog' : 'page';
		$storageDisk = Storage::disk($fileSystem);

		$oldMedia = $this->contentResource->url_content ?? null;
		if ($oldMedia) {
			$oldMedia = str_replace($storageDisk->url(''), '', $oldMedia);
			$storageDisk->delete($oldMedia);
		}

		if (!File::exists($this->publicPath)) {
			File::makeDirectory($this->publicPath, 0775, true, true);
		}

		//$path = $file->store($this->relativePath, $fileSystem);
		$fileName = $contentResource->id_content . '.' . $file->getClientOriginalExtension();
		$path = $file->storeAs($this->relativePath, $fileName, $fileSystem);
		$urlPath = $storageDisk->url($path);

		$contentResource->url_content = $urlPath;
		if($contentResource->isDirty('url_content')) {
			$contentResource->save();
		}

		$this->type_id_content_page = $contentResource->id_content;
		if($this->isDirty('type_id_content_page')) {
			$this->save();
		}

		return $urlPath;
	}

	public function uploadMediaWithoutPersist($file, $withOriginalName)
	{
		$fileSystem = $this->table_rel_content_page == Web_Content_Page::TABLE_REL_CONTENT_PAGE_BLOG ? 'blog' : 'page';
		$storageDisk = Storage::disk($fileSystem);

		$path = $withOriginalName
			? $file->storeAs($this->relativePath, $file->getClientOriginalName(), $fileSystem)
			: $file->store($this->relativePath, $fileSystem);

		$urlPath = $storageDisk->url($path);
		return $urlPath;
	}

	public static function newContentPage($tableRelation, $tableRelationId, $typeContent, $typeContentId = null)
	{
		$order = self::where([
			['table_rel_content_page', $tableRelation],
			['rel_id_content_page', $tableRelationId]
		])->max('order_content_page') + 1;

		$webContentPage = self::create([
			'table_rel_content_page' => $tableRelation,
			'rel_id_content_page' => $tableRelationId,
			'type_content_page' => $typeContent,
			'type_id_content_page' => $typeContentId,
			'order_content_page' => $order
		]);

		return $webContentPage;
	}

}
