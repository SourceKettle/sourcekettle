// Copyright (C) 2015 SourceKettle Development Team
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//      http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.



/**
 * @fileoverview
 * Registers a language handler for Gherkin. Based roughly on the SQL highlighter and hastily hacked together. Will be replaced at some point probably.
 *
 *
 * To use, include prettify.js and this file in your HTML page.
 * Then put your code in an HTML tag like
 *      <pre class="prettyprint lang-gherkin">(my Gherkin spec)</pre>
 *
 *
 * @author amn@ecs.soton.ac.uk
 */

PR['registerLangHandler'](
    PR['createSimpleLexer'](
	[],
        [
	 // A list of Gherkin keywords
         [PR['PR_KEYWORD'], /^\s*((Feature|Scenarios|Scenario Outline|Scenario|Background|Examples|Given|When|Then|And|But):?)/i, null],
        ]),
    ['gherkin']);
