/*************************************************************
 *
 *  MathJax/jax/output/SVG/fonts/TeX/svg/Typewriter/Regular/CombDiacritMarks.js
 *
 *  Copyright (c) 2011-2015 The MathJax Consortium
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 *
 */

MathJax.Hub.Insert(
    MathJax.OutputJax.SVG.FONTDATA.FONTS['MathJax_Typewriter'],
    {
        // COMBINING GRAVE ACCENT
        0x300: [
            611,
            -485,
            0,
            -409,
            -195,
            '-409 569Q-409 586 -399 596T-377 610Q-376 610 -372 610T-365 611Q-355 610 -284 588T-210 563Q-195 556 -195 537Q-195 533 -197 522T-208 498T-229 485Q-238 485 -312 508T-388 533Q-400 538 -405 552Q-409 559 -409 569'
        ],

        // COMBINING ACUTE ACCENT
        0x301: [
            611,
            -485,
            0,
            -331,
            -117,
            '-297 485Q-315 485 -323 505T-331 537Q-331 556 -316 563Q-307 569 -170 610Q-169 610 -165 610T-157 611Q-141 609 -131 600T-119 584T-117 569Q-117 555 -124 545T-138 533Q-140 531 -214 508T-297 485'
        ],

        // COMBINING CIRCUMFLEX ACCENT
        0x302: [
            611,
            -460,
            0,
            -429,
            -97,
            '-387 460Q-404 460 -416 479T-429 512Q-429 527 -419 534Q-416 536 -347 571T-272 609Q-269 611 -261 611Q-254 610 -182 574Q-168 567 -156 561T-136 550T-123 543T-114 538T-109 535T-105 532T-103 529T-100 525Q-97 518 -97 512Q-97 498 -109 479T-139 460H-141Q-148 460 -209 496L-263 526L-317 496Q-378 460 -387 460'
        ],

        // COMBINING TILDE
        0x303: [
            611,
            -466,
            0,
            -438,
            -88,
            '-400 467Q-412 467 -425 480T-438 509Q-437 520 -414 543Q-353 602 -316 609Q-306 611 -301 611Q-279 611 -262 596T-235 566T-221 551Q-206 551 -158 594Q-142 610 -129 610H-125Q-114 610 -101 597T-88 568Q-89 557 -112 534Q-177 469 -220 466Q-247 466 -265 481T-291 511T-305 526Q-320 526 -368 483Q-384 467 -396 467H-400'
        ],

        // COMBINING MACRON
        0x304: [
            578,
            -500,
            0,
            -452,
            -74,
            '-429 500Q-440 504 -445 511T-450 522T-452 536Q-452 552 -451 556Q-445 571 -434 574T-379 578Q-369 578 -330 578T-261 577H-96Q-94 575 -90 573T-85 569T-81 564T-77 558T-75 550T-74 538Q-74 522 -78 515T-96 500H-429'
        ],

        // COMBINING BREVE
        0x306: [
            611,
            -504,
            0,
            -447,
            -79,
            '-446 579Q-446 611 -412 611H-407Q-383 609 -378 599T-358 587Q-340 583 -263 583H-235Q-159 583 -152 593Q-145 611 -120 611H-117H-115Q-79 611 -79 577Q-80 552 -95 536T-140 514T-191 506T-251 504H-263H-274Q-311 504 -334 505T-386 513T-431 536T-446 579'
        ],

        // COMBINING DIAERESIS
        0x308: [
            612,
            -519,
            0,
            -421,
            -104,
            '-421 565Q-421 590 -405 600T-370 611Q-350 611 -345 610Q-308 599 -308 565Q-308 545 -323 532T-359 519H-366H-370Q-405 519 -418 547Q-421 553 -421 565ZM-218 565Q-218 580 -208 593T-179 610Q-177 610 -175 610T-171 611Q-170 612 -158 612Q-130 611 -117 597T-104 565T-116 534T-160 519H-167Q-189 519 -203 532T-218 565'
        ],

        // COMBINING RING ABOVE
        0x30A: [
            619,
            -499,
            0,
            -344,
            -182,
            '-344 558Q-344 583 -321 601T-262 619Q-225 619 -204 600T-182 560Q-182 536 -205 518T-264 499Q-301 499 -322 519T-344 558ZM-223 559Q-223 570 -234 579T-261 588T-289 580T-303 559Q-303 549 -293 540T-263 530T-234 539T-223 559'
        ],

        // COMBINING CARON
        0x30C: [
            577,
            -449,
            0,
            -427,
            -99,
            '-427 525Q-427 542 -417 559T-392 577Q-385 577 -323 553L-263 530L-203 553Q-143 576 -136 576Q-118 576 -109 559T-99 525Q-99 508 -107 502T-161 481Q-177 475 -186 472Q-256 449 -263 449Q-272 449 -339 472T-412 498Q-420 501 -423 508T-427 520V525'
        ]
    }
);

MathJax.Ajax.loadComplete( MathJax.OutputJax.SVG.fontDir + "/Typewriter/Regular/CombDiacritMarks.js" );