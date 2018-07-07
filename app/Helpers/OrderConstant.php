<?php
namespace App\Helpers;

abstract class OrderConstant {

	const ASSET_TYPES = [
		1 => 'Giấy',
		2 => 'Vật tư'
	];

	const SIZE_A0 = 0;
	const SIZE_A1 = 1;
	const SIZE_A2 = 2;
	const SIZE_A3 = 3;
	const SIZE_A4 = 4;

	// Kích cỡ giấy
	const SIZE = [
		self::SIZE_A0 => 'A1',
		self::SIZE_A1 => 'A1',
		self::SIZE_A2 => 'A2',
		self::SIZE_A3 => 'A3',
		self::SIZE_A4 => 'A4'
	];

	// Băng dính
	const TAPE = [
		1 => 'Băng dính xi',
		2 => 'Băng dính trơn'
	];

	// Cán
	const CAN = [
		1 => 'Cán'
	];

	// Gáy sách
	const SPINE = [
		1 => 'Gáy xoắn',
		2 => 'Gáy nhựa',
		3 => 'Gáy vải'
	];

	// Keo nhiệt
	const GLUE = [
		1 => 'Keo nhiệt'
	];

	// Loại màu in
	const COLOR = [
		1 => 'In màu'
	];

	// Màu bìa
	const COVER_COLOR = [
		1 => 'In màu',
		2 => 'Ép nhũ bạc',
		3 => 'Ép nhũ vàng'
	];

	const COVER_TYPE = [
		1 => 'Còng file',
		2 => 'Bìa ngoại'
	];

	// Bóng kính
	const PLASTIC = [
		1 => 'Bóng kính dày',
		2 => 'Bóng kính trung',
		3 => 'Bóng kính mỏng'
	];

	// In 1 mặt
	const ODD_PAGE = [
		0 => 'In 2 mặt',
		1 => 'In 1 mặt'
	];

	// Đục lỗ chia file
	const HOLE = [
		1 => 'Đục lỗ chia file'
	];
}