﻿<?php
/*
** Zabbix
** Copyright (C) 2001-2017 Zabbix SIA
**
** This program is free software; you can redistribute it and/or modify
** it under the terms of the GNU General Public License as published by
** the Free Software Foundation; either version 2 of the License, or
** (at your option) any later version.
**
** This program is distributed in the hope that it will be useful,
** but WITHOUT ANY WARRANTY; without even the implied warranty of
** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
** GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License
** along with this program; if not, write to the Free Software
** Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
**/


class CSimpleIntervalParserTest extends PHPUnit_Framework_TestCase {

	/**
	 * An array of simple intervals and parsed results.
	 */
	public static function testProvider() {
		return [
			// success
			[
				'5', 0,
				[
					'rc' => CParser::PARSE_SUCCESS,
					'match' => '5'
				]
			],
			[
				'10s', 0,
				[
					'rc' => CParser::PARSE_SUCCESS,
					'match' => '10s'
				]
			],
			[
				'30m', 0,
				[
					'rc' => CParser::PARSE_SUCCESS,
					'match' => '30m'
				]
			],
			[
				'604800', 0,
				[
					'rc' => CParser::PARSE_SUCCESS,
					'match' => '604800'
				]
			],
			[
				'5h', 0,
				[
					'rc' => CParser::PARSE_SUCCESS,
					'match' => '5h'
				]
			],
			[
				'3d', 0,
				[
					'rc' => CParser::PARSE_SUCCESS,
					'match' => '3d'
				]
			],
			[
				'2w', 0,
				[
					'rc' => CParser::PARSE_SUCCESS,
					'match' => '2w'
				]
			],
			// partial success
			[
				'random text.....10s....text', 16,
				[
					'rc' => CParser::PARSE_SUCCESS_CONT,
					'match' => '10s'
				]
			],
			[
				'2ww', 0,
				[
					'rc' => CParser::PARSE_SUCCESS_CONT,
					'match' => '2w'
				]
			],
			[
				'9z', 0,
				[
					'rc' => CParser::PARSE_SUCCESS_CONT,
					'match' => '9'
				]
			],
			[
				'9/', 0,
				[
					'rc' => CParser::PARSE_SUCCESS_CONT,
					'match' => '9'
				]
			],
			[
				'10sm', 0,
				[
					'rc' => CParser::PARSE_SUCCESS_CONT,
					'match' => '10s'
				]
			],
			[
				'300;', 0,
				[
					'rc' => CParser::PARSE_SUCCESS_CONT,
					'match' => '300'
				]
			],
			[
				'1y', 0,
				[
					'rc' => CParser::PARSE_SUCCESS_CONT,
					'match' => '1'
				]
			],
			// fail
			[
				'', 0,
				[
					'rc' => CParser::PARSE_FAIL,
					'match' => ''
				]
			],
			[
				's', 0,
				[
					'rc' => CParser::PARSE_FAIL,
					'match' => ''
				]
			],
			[
				'qwerty', 0,
				[
					'rc' => CParser::PARSE_FAIL,
					'match' => ''
				]
			],
			[
				' 10s', 0,
				[
					'rc' => CParser::PARSE_FAIL,
					'match' => ''
				]
			]
		];
	}

	/**
	 * @dataProvider testProvider
	 *
	 * @param string $source
	 * @param int    $pos
	 * @param array  $expected
	*/
	public function testParse($source, $pos, $expected) {
		static $parser = null;

		if ($parser === null) {
			$parser = new CSimpleIntervalParser();
		}

		$this->assertSame($expected, [
			'rc' => $parser->parse($source, $pos),
			'match' => $parser->getMatch()
		]);
		$this->assertSame(strlen($expected['match']), $parser->getLength());
	}
}
