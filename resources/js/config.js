/**
 * Defines the API route we are using.
 */
var api_url = "";

switch (process.env.NODE_ENV) {
  case "development":
    api_url = "http://api.iqqtv";
    break;
  case "production":
    api_url = "http://tvapi.f1good.com";
    break;
}

export const ROAST_CONFIG = {
  API_URL: api_url
};
