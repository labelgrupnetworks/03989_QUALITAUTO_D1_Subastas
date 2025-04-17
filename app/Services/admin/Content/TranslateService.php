<?php

namespace App\Services\admin\Content;

use App\Models\V5\WebTranslate;
use App\Models\V5\WebTranslateHeaders;
use App\Models\V5\WebTranslateKey;
use Illuminate\Support\Facades\Session;

class TranslateService
{
	public function getTranslate($head, $lang)
	{
		$res = WebTranslateHeaders::query()
			->select([
				'web_translate_headers.key_header',
				'web_translate_key.key_translate',
				'web_translate.web_translation',
				'web_translate.id_translate',
				'web_translate.id_key_translate'
			])
			->joinTranslateKey()
			->leftJoinTranslate($lang)
			->where('web_translate_headers.key_header', $head)
			->orderBy('web_translate_headers.key_header')
			->get()
			->keyBy('key_translate')
			->all();

		return $res;
	}

	public function updateTrans($id, $value, $lang)
	{
		$userName = Session::get('user.name');

		WebTranslate::query()
			->where([
				'id_key_translate' => $id,
				'lang' => $lang
			])
			->update([
				'web_translation' => $value,
				'user_modificacion' => $userName
			]);
	}

	public function deleteTrans($id_key_translate, $lang)
	{
		WebTranslate::query()
			->where([
				'id_key_translate' => $id_key_translate,
				'lang' => $lang
			])
			->delete();
	}

	public function idKey($key, $lang)
	{
		return WebTranslate::query()
			->where([
				'id_key_translate' => $key,
				'lang' => $lang
			])
			->first();
	}

	/**
	 * @param $keyTranslate
	 * @param $idHeader
	 * @return int|null
	 */
	public function idKeyTranslateHeader($keyTranslate, $idHeader): ?int
	{
		return WebTranslateKey::where([
			'id_headers_translate' => $idHeader,
			'key_translate' => $keyTranslate
		])->value('id_key');
	}

	public function insertTrans($id_key, $value, $lang)
	{
		$userName = Session::get('user.name');
		WebTranslate::create([
			'id_key_translate' => $id_key,
			'id_translate' => WebTranslate::max('id_translate') + 1,
			'lang' => $lang,
			'web_translation' => $value,
			'user_modificacion' => $userName,
		]);
	}

	/**
	 * @param $key
	 * @return int|null
	 */
	public function getIdHeaderToTranslateHeaderByKey($key): ?int
	{
		return WebTranslateHeaders::query()
			->where('key_header', $key)
			->value('id_headers');
	}

	/**
	 * @param $id_headers
	 * @param $key_translate
	 * @return int
	 */
	public function insertKey($id_headers, $key_translate): int
	{
		$newTranslateKeyId = WebTranslateKey::max('id_key') + 1;
		WebTranslateKey::create([
			'id_key' => $newTranslateKeyId,
			'id_headers_translate' => $id_headers,
			'key_translate' => $key_translate
		]);
		return $newTranslateKeyId;
	}

	/**
	 * @param $keyHeader
	 * @return int
	 */
	public function insertWebTranslateHeader($keyHeader): int
	{
		$newId = WebTranslateHeaders::max('id_headers') + 1;
		WebTranslateHeaders::create([
			'id_headers' => $newId,
			'key_header' => $keyHeader
		]);
		return $newId;
	}
}
