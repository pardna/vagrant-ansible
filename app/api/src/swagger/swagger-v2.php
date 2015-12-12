<?php
/**
 * @SWG\Swagger(
 *     schemes={"http"},
 *     host="192.168.56.101:444/api/web/index.php",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Pardna Site API",
 *         description="This is a server test the Pardna site API. This UI is built using Swagger. You can find out more about Swagger at <a href=""http://swagger.io"">http://swagger.io</a> or on irc.freenode.net, #swagger.  For this sample, you can use the api key ""special-key"" to test the authorization filters. Need more time to understand the authorization filters ",
 *         termsOfService="http://helloreverb.com/terms/",
 *         @SWG\Contact(
 *             email="apiteam@wordnik.com"
 *         ),
 *         @SWG\License(
 *             name="Apache 2.0",
 *             url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *         )
 *     ),
 *     @SWG\ExternalDocumentation(
 *         description="Find out more about Swagger",
 *         url="http://swagger.io"
 *     ),
 *     @SWG\Definition(
 *        @SWG\Xml(name="TokenDefault"),
 *        definition = "TokenDefault",
 * 			  required={"success", "token"},
 * 			  @SWG\Property(property="success", type="boolean"),
 * 			  @SWG\Property(property="token", type="string", description="Json Web Token"),
 *        @SWG\Property(property="stacktrace", type="string", description="stack trace")
 * 		),
 *    @SWG\Definition(
 *        @SWG\Xml(name="User"),
 *        definition = "User",
 * 			  required={"email", "fullname","password"},
 * 			  @SWG\Property(
 *          property="user",
 *          type="object",
 * 			    required={"email", "fullname","password"},
 * 			    @SWG\Property(property="email", type="string"),
 *          @SWG\Property(property="fullname", type="string"),
 * 			    @SWG\Property(property="password", type="string", description="Password")
 *        )
 * 		),
 *    @SWG\Definition(
 *        @SWG\Xml(name="ErrorDefault"),
 *        definition = "ErrorDefault",
 * 			  required={"statusCode", "message"},
 * 			  @SWG\Property(property="statusCode", type="boolean"),
 * 			  @SWG\Property(property="message", type="string", description="Message detailing error")
 * 		),
 *    @SWG\Definition(
 *      @SWG\Xml(name="LoginUser"),
 *      definition = "LoginUser",
 * 			required={"username", "password"},
 * 			@SWG\Property(property="username", type="string", description="Username"),
 * 			@SWG\Property(property="password", type="string", description="Password")
 * 		)
 * )
 */
