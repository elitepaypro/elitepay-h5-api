<?php

namespace App\Constants;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Categires
{
    const DATA = [
        [
            "id" =>1,
            "parent_id" =>0,
            "name" =>"餐饮",
            "categories" => [
                [
                    "id" =>1001,
                    "parent_id" =>1,
                    "name" =>"奶茶咖啡",
                    "categories" => [
                        [
                            "id" =>11,
                            "parent_id" =>1,
                            "name" =>"咖啡厅",
                            "categories" =>[]
                        ],
                        [
                            "id" =>12,
                            "parent_id" =>1,
                            "name" =>"奶茶/果汁",
                            "categories" =>[]
                        ],
                        [
                            "id" =>64,
                            "parent_id" =>1,
                            "name" =>"饮品店",
                            "categories" =>[]
                        ],
                        [
                            "id" =>167,
                            "parent_id" =>1,
                            "name" =>"酸奶鲜奶",
                            "categories" =>[]
                        ],

                    ],
                ],
                [
                    "id" =>1002,
                    "parent_id" =>1,
                    "name" =>"甜品蛋糕",
                    "categories" => [
                        [
                            "id" =>169,
                            "parent_id" =>1,
                            "name" =>"冰淇凌",
                            "categories" =>[]
                        ],
                        [
                            "id" =>60,
                            "parent_id" =>1,
                            "name" =>"面包甜点",
                            "categories" =>[]
                        ],
                        [
                            "id" =>23,
                            "parent_id" =>1,
                            "name" =>"面包蛋糕",
                            "categories" =>[]
                        ],
                    ],
                ],
                [
                    "id" =>1003,
                    "parent_id" =>1,
                    "name" =>"快餐简餐",
                    "categories" => [
                        [
                            "id" =>37,
                            "parent_id" =>1,
                            "name" =>"干锅/香锅",
                            "categories" =>[]
                        ],
                        [
                            "id" =>13,
                            "parent_id" =>1,
                            "name" =>"麻辣烫",
                            "categories" =>[]
                        ],
                        [
                            "id" =>8,
                            "parent_id" =>1,
                            "name" =>"西式快餐/汉堡",
                            "categories" =>[]
                        ],
                        [
                            "id" =>30,
                            "parent_id" =>1,
                            "name" =>"快餐简餐",
                            "categories" =>[]
                        ],
                        [
                            "id" =>17,
                            "parent_id" =>1,
                            "name" =>"披萨",
                            "categories" =>[]
                        ],
                        [
                            "id" =>22,
                            "parent_id" =>1,
                            "name" =>"排骨米饭",
                            "categories" =>[]
                        ],
                        [
                            "id" =>19,
                            "parent_id" =>1,
                            "name" =>"米粉",
                            "categories" =>[]
                        ],
                    ],
                ],
                [
                    "id" =>1004,
                    "parent_id" =>1,
                    "name" =>"小吃快餐",
                    "categories" => [
                        [
                            "id" =>9,
                            "parent_id" =>1,
                            "name" =>"卤味鸭脖",
                            "categories" =>[]
                        ],
                        [
                            "id" =>34,
                            "parent_id" =>1,
                            "name" =>"饺子",
                            "categories" =>[]
                        ],
                        [
                            "id" =>20,
                            "parent_id" =>1,
                            "name" =>"馄饨|抄手",
                            "categories" =>[]
                        ],
                        [
                            "id" =>21,
                            "parent_id" =>1,
                            "name" =>"寿司",
                            "categories" =>[]
                        ],
                        [
                            "id" =>31,
                            "parent_id" =>1,
                            "name" =>"锅盔",
                            "categories" =>[]
                        ],
                        [
                            "id" =>35,
                            "parent_id" =>1,
                            "name" =>"过桥米线",
                            "categories" =>[]
                        ],
                        [
                            "id" =>28,
                            "parent_id" =>1,
                            "name" =>"粥",
                            "categories" =>[]
                        ],
                        [
                            "id" =>14,
                            "parent_id" =>1,
                            "name" =>"熟食熏酱",
                            "categories" =>[]
                        ],
                        [
                            "id" =>67,
                            "parent_id" =>1,
                            "name" =>"小吃快餐",
                            "categories" =>[]
                        ],
                        [
                            "id" =>24,
                            "parent_id" =>1,
                            "name" =>"炸酱面",
                            "categories" =>[]
                        ],
                        [
                            "id" =>10,
                            "parent_id" =>1,
                            "name" =>"炸鸡炸串",
                            "categories" =>[]
                        ],
                        [
                            "id" =>36,
                            "parent_id" =>1,
                            "name" =>"生煎",
                            "categories" =>[]
                        ],

                    ],
                ],
                [
                    "id" =>1005,
                    "parent_id" =>1,
                    "name" =>"异域料理",
                    "categories" => [
                        [
                            "id" =>44,
                            "parent_id" =>1,
                            "name" =>"东南亚菜",
                            "categories" =>[]
                        ],
                        [
                            "id" =>65,
                            "parent_id" =>1,
                            "name" =>"日本菜",
                            "categories" =>[]
                        ],
                        [
                            "id" =>50,
                            "parent_id" =>1,
                            "name" =>"西餐",
                            "categories" =>[]
                        ],

                        [
                            "id" =>59,
                            "parent_id" =>1,
                            "name" =>"韩国菜",
                            "categories" =>[]
                        ],

                    ],
                ],
                [
                    "id" =>1006,
                    "parent_id" =>1,
                    "name" =>"地方特色菜",
                    "categories" => [
                        [
                            "id" =>53,
                            "parent_id" =>1,
                            "name" =>"中东菜",
                            "categories" =>[]
                        ],
                        [
                            "id" =>46,
                            "parent_id" =>1,
                            "name" =>"非洲菜",
                            "categories" =>[]
                        ],
                        [
                            "id" =>39,
                            "parent_id" =>1,
                            "name" =>"闽菜",
                            "categories" =>[]
                        ],
                        [
                            "id" =>61,
                            "parent_id" =>1,
                            "name" =>"东北菜",
                            "categories" =>[]
                        ],[
                            "id" =>25,
                            "parent_id" =>1,
                            "name" =>"茶餐厅",
                            "categories" =>[]
                        ],
                        [
                            "id" =>40,
                            "parent_id" =>1,
                            "name" =>"西北菜",
                            "categories" =>[]
                        ],
                        [
                            "id" =>42,
                            "parent_id" =>1,
                            "name" =>"农家菜",
                            "categories" =>[]
                        ],
                        [
                            "id" =>49,
                            "parent_id" =>1,
                            "name" =>"北京菜",
                            "categories" =>[]
                        ],
                        [
                            "id" =>58,
                            "parent_id" =>1,
                            "name" =>"川菜",
                            "categories" =>[]
                        ],
                        [
                            "id" =>52,
                            "parent_id" =>1,
                            "name" =>"粤菜",
                            "categories" =>[]
                        ],
                        [
                            "id" =>48,
                            "parent_id" =>1,
                            "name" =>"新疆菜",
                            "categories" =>[]
                        ],
                        [
                            "id" =>66,
                            "parent_id" =>1,
                            "name" =>"本帮江浙菜",
                            "categories" =>[]
                        ],
                        [
                            "id" =>47,
                            "parent_id" =>1,
                            "name" =>"湘菜",
                            "categories" =>[]
                        ],
                        [
                            "id" =>57,
                            "parent_id" =>1,
                            "name" =>"特色菜",
                            "categories" =>[]
                        ],
                        [
                            "id" =>32,
                            "parent_id" =>1,
                            "name" =>"私房菜",
                            "categories" =>[]
                        ],

                    ],
                ],
                [
                    "id" =>1007,
                    "parent_id" =>1,
                    "name" =>"烧烤/烤肉",
                    "categories" => [
                        [
                            "id" =>51,
                            "parent_id" =>1,
                            "name" =>"烧烤",
                            "categories" =>[]
                        ],
                        [
                            "id" =>29,
                            "parent_id" =>1,
                            "name" =>"烤肉",
                            "categories" =>[]
                        ],
                        [
                            "id" =>166,
                            "parent_id" =>1,
                            "name" =>"烤鱼",
                            "categories" =>[]
                        ],

                    ],
                ],
                [
                    "id" =>1008,
                    "parent_id" =>1,
                    "name" =>"火锅冒菜",
                    "categories" => [
                        [
                            "id" =>27,
                            "parent_id" =>1,
                            "name" =>"冒菜",
                            "categories" =>[]
                        ],
                        [
                            "id" =>38,
                            "parent_id" =>1,
                            "name" =>"焖锅",
                            "categories" =>[]
                        ],
                        [
                            "id" =>45,
                            "parent_id" =>1,
                            "name" =>"火锅",
                            "categories" =>[]
                        ],
                    ],
                ],
                [
                    "id" =>1009,
                    "parent_id" =>1,
                    "name" =>"自助餐",
                    "categories" => [
                        [
                            "id" =>55,
                            "parent_id" =>1,
                            "name" =>"自助餐",
                            "categories" =>[]
                        ],

                    ],
                ],
                [
                    "id" =>1010,
                    "parent_id" =>1,
                    "name" =>"鱼鲜海鲜",
                    "categories" => [
                        [
                            "id" =>33,
                            "parent_id" =>1,
                            "name" =>"小龙虾",
                            "categories" =>[]
                        ],[
                            "id" =>26,
                            "parent_id" =>1,
                            "name" =>"酸菜鱼",
                            "categories" =>[]
                        ],[
                            "id" =>56,
                            "parent_id" =>1,
                            "name" =>"鱼鲜",
                            "categories" =>[]
                        ],
                        [
                            "id" =>69,
                            "parent_id" =>1,
                            "name" =>"鱼鲜海鲜",
                            "categories" =>[]
                        ],
                    ],
                ],
                [
                    "id" =>1011,
                    "parent_id" =>1,
                    "name" =>"超市/便利店",
                    "categories" => [
                        [
                            "id" =>7,
                            "parent_id" =>1,
                            "name" =>"超市/便利店",
                            "categories" =>[]
                        ],
                        [
                            "id" =>15,
                            "parent_id" =>1,
                            "name" =>"零食",
                            "categories" =>[]
                        ],

                    ],
                ],
                [
                    "id" =>1012,
                    "parent_id" =>1,
                    "name" =>"其它餐饮",
                    "categories" => [
                        [
                            "id" =>43,
                            "parent_id" =>1,
                            "name" =>"其他地方菜",
                            "categories" =>[]
                        ],
                        [
                            "id" =>62,
                            "parent_id" =>1,
                            "name" =>"水果生鲜",
                            "categories" =>[]
                        ],[
                            "id" =>63,
                            "parent_id" =>1,
                            "name" =>"食品保健",
                            "categories" =>[]
                        ],
                        [
                            "id" =>68,
                            "parent_id" =>1,
                            "name" =>"食品滋补",
                            "categories" =>[]
                        ],
                        [
                            "id" =>168,
                            "parent_id" =>1,
                            "name" =>"高档型酒店",
                            "categories" =>[]
                        ],
                        [
                            "id" =>54,
                            "parent_id" =>1,
                            "name" =>"其他餐饮",
                            "categories" =>[]
                        ],
                    ],
                ],
            ]
        ],
//        [
//            "id" =>2,
//            "parent_id" =>0,
//            "name" =>"丽人",
//            "categories" =>[
//                [
//                    "id" =>75,
//                    "parent_id" =>2,
//                    "name" =>"其他丽人",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>74,
//                    "parent_id" =>2,
//                    "name" =>"养发",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>78,
//                    "parent_id" =>2,
//                    "name" =>"化妆品",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>73,
//                    "parent_id" =>2,
//                    "name" =>"医疗整形",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>79,
//                    "parent_id" =>2,
//                    "name" =>"瑜伽",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>71,
//                    "parent_id" =>2,
//                    "name" =>"纹身",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>80,
//                    "parent_id" =>2,
//                    "name" =>"美发",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>76,
//                    "parent_id" =>2,
//                    "name" =>"美容美体",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>70,
//                    "parent_id" =>2,
//                    "name" =>"美甲",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>77,
//                    "parent_id" =>2,
//                    "name" =>"美睫纹绣",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>72,
//                    "parent_id" =>2,
//                    "name" =>"舞蹈",
//                    "categories" =>[]
//                ]
//            ]
//        ],
//        [
//            "id" =>4,
//            "parent_id" =>0,
//            "name" =>"亲子",
//            "categories" =>[
//                [
//                    "id" =>111,
//                    "parent_id" =>4,
//                    "name" =>"亲子游乐",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>118,
//                    "parent_id" =>4,
//                    "name" =>"亲子购物",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>112,
//                    "parent_id" =>4,
//                    "name" =>"其他亲子",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>115,
//                    "parent_id" =>4,
//                    "name" =>"医疗机构",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>113,
//                    "parent_id" =>4,
//                    "name" =>"婴幼服务",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>110,
//                    "parent_id" =>4,
//                    "name" =>"孕产服务",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>114,
//                    "parent_id" =>4,
//                    "name" =>"孕婴童摄影",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>116,
//                    "parent_id" =>4,
//                    "name" =>"宝宝宴/纪念品",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>117,
//                    "parent_id" =>4,
//                    "name" =>"月子服务",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>109,
//                    "parent_id" =>4,
//                    "name" =>"母婴用品",
//                    "categories" =>[]
//                ]
//            ]
//        ],
//        [
//            "id" =>3,
//            "parent_id" =>0,
//            "name" =>"休闲娱乐",
//            "categories" =>[
//                [
//                    "id" =>89,
//                    "parent_id" =>3,
//                    "name" =>"DIY手工坊",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>100,
//                    "parent_id" =>3,
//                    "name" =>"VR",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>85,
//                    "parent_id" =>3,
//                    "name" =>"互动影院",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>90,
//                    "parent_id" =>3,
//                    "name" =>"其他休闲娱乐",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>82,
//                    "parent_id" =>3,
//                    "name" =>"剧本杀",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>87,
//                    "parent_id" =>3,
//                    "name" =>"团建拓展",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>108,
//                    "parent_id" =>3,
//                    "name" =>"家居",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>93,
//                    "parent_id" =>3,
//                    "name" =>"密室",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>88,
//                    "parent_id" =>3,
//                    "name" =>"成人体验",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>96,
//                    "parent_id" =>3,
//                    "name" =>"按摩/足疗",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>106,
//                    "parent_id" =>3,
//                    "name" =>"文化艺术",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>101,
//                    "parent_id" =>3,
//                    "name" =>"新奇体验",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>102,
//                    "parent_id" =>3,
//                    "name" =>"桌游",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>104,
//                    "parent_id" =>3,
//                    "name" =>"棋牌",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>99,
//                    "parent_id" =>3,
//                    "name" =>"汽车影院",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>81,
//                    "parent_id" =>3,
//                    "name" =>"汽车维修",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>94,
//                    "parent_id" =>3,
//                    "name" =>"洗浴",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>107,
//                    "parent_id" =>3,
//                    "name" =>"洗衣洗鞋",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>103,
//                    "parent_id" =>3,
//                    "name" =>"游戏厅",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>97,
//                    "parent_id" =>3,
//                    "name" =>"真人CS",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>84,
//                    "parent_id" =>3,
//                    "name" =>"私人影院",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>98,
//                    "parent_id" =>3,
//                    "name" =>"网吧/电竞",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>92,
//                    "parent_id" =>3,
//                    "name" =>"舞厅",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>95,
//                    "parent_id" =>3,
//                    "name" =>"茶馆",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>91,
//                    "parent_id" =>3,
//                    "name" =>"轰趴",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>86,
//                    "parent_id" =>3,
//                    "name" =>"酒吧",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>83,
//                    "parent_id" =>3,
//                    "name" =>"采摘/农家乐",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>105,
//                    "parent_id" =>3,
//                    "name" =>"鬼屋",
//                    "categories" =>[]
//                ]
//            ]
//        ],
//        [
//            "id" =>5,
//            "parent_id" =>0,
//            "name" =>"教育培训",
//            "categories" =>[
//                [
//                    "id" =>129,
//                    "parent_id" =>5,
//                    "name" =>"STEM",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>148,
//                    "parent_id" =>5,
//                    "name" =>"主持表演/模特",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>119,
//                    "parent_id" =>5,
//                    "name" =>"书店",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>140,
//                    "parent_id" =>5,
//                    "name" =>"书法",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>121,
//                    "parent_id" =>5,
//                    "name" =>"兴趣生活",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>120,
//                    "parent_id" =>5,
//                    "name" =>"其他教育培训",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>146,
//                    "parent_id" =>5,
//                    "name" =>"国学",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>133,
//                    "parent_id" =>5,
//                    "name" =>"在线教育",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>130,
//                    "parent_id" =>5,
//                    "name" =>"学历提升",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>122,
//                    "parent_id" =>5,
//                    "name" =>"学科辅导",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>136,
//                    "parent_id" =>5,
//                    "name" =>"幼儿园",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>125,
//                    "parent_id" =>5,
//                    "name" =>"才艺培训",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>128,
//                    "parent_id" =>5,
//                    "name" =>"托班/幼儿园",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>144,
//                    "parent_id" =>5,
//                    "name" =>"托班/托儿所",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>126,
//                    "parent_id" =>5,
//                    "name" =>"教育院校",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>124,
//                    "parent_id" =>5,
//                    "name" =>"早教",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>141,
//                    "parent_id" =>5,
//                    "name" =>"棋艺",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>142,
//                    "parent_id" =>5,
//                    "name" =>"游学/冬夏令营",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>127,
//                    "parent_id" =>5,
//                    "name" =>"留学",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>147,
//                    "parent_id" =>5,
//                    "name" =>"科创教育基地",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>145,
//                    "parent_id" =>5,
//                    "name" =>"科学探索",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>149,
//                    "parent_id" =>5,
//                    "name" =>"绘本馆",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>139,
//                    "parent_id" =>5,
//                    "name" =>"美术",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>123,
//                    "parent_id" =>5,
//                    "name" =>"职业培训",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>135,
//                    "parent_id" =>5,
//                    "name" =>"自习室",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>138,
//                    "parent_id" =>5,
//                    "name" =>"舞蹈培训",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>132,
//                    "parent_id" =>5,
//                    "name" =>"语言培训",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>143,
//                    "parent_id" =>5,
//                    "name" =>"身心健康教育",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>134,
//                    "parent_id" =>5,
//                    "name" =>"运动培训",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>137,
//                    "parent_id" =>5,
//                    "name" =>"音乐",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>131,
//                    "parent_id" =>5,
//                    "name" =>"驾驶培训",
//                    "categories" =>[]
//                ]
//            ]
//        ],
//        [
//            "id" =>6,
//            "parent_id" =>0,
//            "name" =>"结婚",
//            "categories" =>[
//                [
//                    "id" =>154,
//                    "parent_id" =>6,
//                    "name" =>"个性写真",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>160,
//                    "parent_id" =>6,
//                    "name" =>"其他结婚",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>150,
//                    "parent_id" =>6,
//                    "name" =>"司仪主持",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>151,
//                    "parent_id" =>6,
//                    "name" =>"婚宴/宴会场地",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>158,
//                    "parent_id" =>6,
//                    "name" =>"婚庆公司",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>152,
//                    "parent_id" =>6,
//                    "name" =>"婚戒首饰",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>157,
//                    "parent_id" =>6,
//                    "name" =>"婚礼喜品",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>162,
//                    "parent_id" =>6,
//                    "name" =>"婚礼跟拍",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>153,
//                    "parent_id" =>6,
//                    "name" =>"婚纱摄影",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>155,
//                    "parent_id" =>6,
//                    "name" =>"婚纱礼服",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>164,
//                    "parent_id" =>6,
//                    "name" =>"婚车租赁",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>156,
//                    "parent_id" =>6,
//                    "name" =>"彩妆造型",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>163,
//                    "parent_id" =>6,
//                    "name" =>"情感咨询",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>159,
//                    "parent_id" =>6,
//                    "name" =>"旅拍",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>161,
//                    "parent_id" =>6,
//                    "name" =>"西服定制",
//                    "categories" =>[]
//                ],
//                [
//                    "id" =>165,
//                    "parent_id" =>6,
//                    "name" =>"跟拍",
//                    "categories" =>[]
//                ]
//            ]
//        ],
    ];
}
