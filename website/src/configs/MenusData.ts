export const HeaderMenusList: any[] = [
    {title:'首页', target:'', href:'/home', default: true},
    {title:'学校概况', target:'', href:'', default: false, children:
        [
          {title: '学校简介', target:'', href: '/dashboard' },
          {title: '学校领导', target:'', href: '/leader' },
          {title: '学校风采', target:'', href: '/teacher' },
          {title: '组织机构', target:'', href: '/dashboard' },
        ]
    },
    {title:'学校新闻', target:'', href:'', default: false, children:
      [
        {title: '新闻咨询', target:'', href: '/news' },
        {title: '校园公告', target:'', href: '/news' },
      ]
    },
    {title:'教育教学', target:'', href:'', default: false, children:
      [
        {title: '学校日历', target:'', href: '/calendar' },
        {title: '教学看板', target:'', href: '/logistics' },
        {title: '教学改革', target:'', href: '/teacher' },
        {title: '人陪方案', target:'', href: '/dashboard' },
        {title: '教学成果奖', target:'', href: '/teacher' },
      ]
    },
    {title:'校园风采', target:'', href:'', default: false, children:
      [
        {title: '学生风采', target:'', href: '/campus/Student' },
        {title: '老师风采', target:'', href: '/campus/Teacher' },
        {title: '优秀老师', target:'', href: '/campus/ExcellentTeacher' },
        {title: '优秀班主任', target:'', href: '/campus/ExcellentClassMaster' },
        {title: '优秀学生', target:'', href: '/campus/ExcellentStudent' },
      ]
    },
    {title:'学生资助', target:'', href:'', default: false, children:
      [
        {title: '资助政策', target:'', href: '/fund' },
        {title: '资助公告', target:'', href: '/fund' },
        {title: '资助动态', target:'', href: '/fund' },
      ]
    },
    {title:'党团工会', target:'', href:'', default: false, children:
      [
        {title: '职工之家', target:'', href: '/party/staff' },
        {title: '党建工作', target:'', href: '/party/affairs' },
      ]
    },
    {title:'招生就业', target:'', href:'', default: false, children:
      [
        {title: '招生信息', target:'', href: '/recruitment' },
        {title: '就业信息', target:'', href: '/employment' },
        {title: '在线报名', target:'', href: '/register' },
      ]
    },
    {title:'校友之家', target:'', href:'', default: false, children:
      [
        {title: '校友动态', target:'', href: '/alumni/news' },
        {title: '校友联络', target:'', href: '/alumni/contact' },
        {title: '校友风采', target:'', href: '/alumni/achievements' },
        {title: '流金岁月', target:'', href: '/alumni/moments' },
      ]
    },
  ]
