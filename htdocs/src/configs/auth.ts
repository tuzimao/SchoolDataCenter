export const AppSchoolConfigMap: any  = {}
AppSchoolConfigMap['production']       = ["/api/", 'SchoolAI', "auth/menus.php", "/api/"] //For Exe Package
AppSchoolConfigMap['development']     = ["http://localhost:8888/api/", 'SchoolAI', "auth/menus.php", "http://localhost:8888/api/"] //For Local Development
AppSchoolConfigMap['dandian.net']     = ["https://demoapi.dandian.net/api/", 'SchoolAI', "auth/menus.php", "https://demoapi.dandian.net/api/"] // For School and Demoapi.dandian.net
AppSchoolConfigMap['fdzyzz.com']      = ["https://fdzz.dandian.net:8443/api/", '福鼎职中', "auth/menus.php", "https://fdzz.dandian.net:8443/api/"]
AppSchoolConfigMap['fjsmnx.com']      = ["https://dsj.fjsmlyxx.com:1443/api/", '三明林业', "auth/menus.php", "https://dsj.fjsmlyxx.com:1443/api/"]
const APP_URL = AppSchoolConfigMap["dandian.net"][0]
const AppName = AppSchoolConfigMap["dandian.net"][1]
const indexMenuspath = AppSchoolConfigMap["dandian.net"][2]
const backEndApiAiBaseUrl = AppSchoolConfigMap["dandian.net"][3]

export const authConfig = {
    AppName: AppName,
    AppLogo: '/icons/' + "dandian.net" + '/icon256.png',
    AppMarkId: "dandian.net",
    AppSchoolConfigMap: AppSchoolConfigMap,
    indexMenuspath: indexMenuspath,
    loginEndpoint: APP_URL + 'jwt.php?action=login',
    logoutEndpoint: APP_URL + 'jwt.php?action=logout',
    refreshEndpoint: APP_URL + 'jwt.php?action=refresh',
    registerEndpoint: APP_URL + 'jwt/register',
    backEndApiHost: APP_URL,
    backEndApiAiBaseUrl: backEndApiAiBaseUrl,
    indexImageUrl: '/images/school/' + "dandian.net" + '/index.jpg',
    logoUrl: '/images/school/' + "dandian.net" + '/logo.png'
}

export const defaultConfig = {
  Github: 'https://github.com/SmartSchoolAI/SchoolDataCenter',
  Docs: 'https://docs.dandian.net',
  AppVersion: '20250430',
  AppVersionType: '试用版本',
  defaultLanguage: 'zh',
  storageTokenKeyName: 'accessToken',
  storageAccessKeyName: 'accessKey',
  storageMainMenus: 'storageMainMenus',
  storageChatApp: 'storageChatApp',
  storageMyCoursesList: 'storageMyCoursesList',
}
