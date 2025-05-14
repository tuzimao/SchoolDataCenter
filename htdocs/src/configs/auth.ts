export const AppSchoolConfigMap: any  = {}
AppSchoolConfigMap['production']       = ["/api/", 'SchoolAI', "auth/menus.php", "/api/"] //For Exe Package
AppSchoolConfigMap['development']     = ["http://localhost:8888/api/", 'SchoolAI', "auth/menus.php", "http://localhost:8888/api/"] //For Local Development
AppSchoolConfigMap['dandian.net']     = ["https://demoapi.dandian.net/api/", 'SchoolAI', "auth/menus.php", "https://demoapi.dandian.net/api/"] // For School and Demoapi.dandian.net
AppSchoolConfigMap['fdzyzz.com']      = ["https://fdzz.dandian.net:8443/api/", '福鼎职中', "auth/menus.php", "https://fdzz.dandian.net:8443/api/"]
AppSchoolConfigMap['fjsmnx.com']      = ["https://dsj.fjsmlyxx.com:1443/api/", '三明林业', "auth/menus.php", "https://dsj.fjsmlyxx.com:1443/api/"]

const AppMarkId   = "dandian.net"; //这一行, 一定要写在第8行, 否则需要同时修改Github 的 workflows 文件
const AppVersion  = '20250515'; //需要刷新客户端JS时需要更新此值
const APP_URL = AppSchoolConfigMap[AppMarkId][0]
const AppName = AppSchoolConfigMap[AppMarkId][1]
const indexMenuspath = AppSchoolConfigMap[AppMarkId][2]
const backEndApiAiBaseUrl = AppSchoolConfigMap[AppMarkId][3]

export const authConfig = {
    AppName: AppName,
    AppLogo: '/icons/' + AppMarkId + '/icon256.png',
    AppMarkId: AppMarkId,
    AppSchoolConfigMap: AppSchoolConfigMap,
    indexMenuspath: indexMenuspath,
    loginEndpoint: APP_URL + 'jwt.php?action=login',
    logoutEndpoint: APP_URL + 'jwt.php?action=logout',
    refreshEndpoint: APP_URL + 'jwt.php?action=refresh',
    registerEndpoint: APP_URL + 'jwt/register',
    authorizationEndpoint: APP_URL + 'oauth/authorization.php',
    backEndApiHost: APP_URL,
    backEndApiAiBaseUrl: backEndApiAiBaseUrl,
    indexImageUrl: '/images/school/' + AppMarkId + '/index.jpg',
    logoUrl: '/images/school/' + AppMarkId + '/logo.png'
}

export const defaultConfig = {
  Github: 'https://github.com/SmartSchoolAI/SchoolDataCenter',
  Docs: 'https://docs.dandian.net',
  AppVersion: AppVersion,
  AppVersionType: '试用版本',
  defaultLanguage: 'zh',
  storageTokenKeyName: 'accessToken',
  storageAccessKeyName: 'accessKey',
  storageMainMenus: 'storageMainMenus',
  storageChatApp: 'storageChatApp',
  storageMyCoursesList: 'storageMyCoursesList',
}
