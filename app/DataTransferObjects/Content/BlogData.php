<?php

namespace App\DataTransferObjects\Content;

class BlogData
{
	public function __construct(
		public string $id,
		public string $title,
		public string $categories_web,
		public string $sub_categories_web,
		public string $img,
		public string $date,
		public string $category_principal,
		public ?string $author_web_blog
	) {}

	public static function fromRequest($request): self
	{

		return new self(
			$request->input('id', 0),
			$request->input('title', ''),
			implode(',', $request->input('sec', [])),
			implode(',', $request->input('sub_categ', [])),
			$request->input('file_url', ''),
			$request->input('date', ''),
			$request->input('categ_blog_principal', ''),
			$request->input('author', '')
		);
	}


}
