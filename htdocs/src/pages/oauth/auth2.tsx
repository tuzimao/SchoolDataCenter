import { ReactNode } from 'react'
import {
  Box,
  Button,
  Card,
  CardContent,
  Typography,
  Avatar,
  Divider,
  Collapse,
  IconButton,
} from "@mui/material";
import GitHubIcon from "@mui/icons-material/GitHub";
import BlankLayout from 'src/@core/layouts/BlankLayout'

const GitHubAuthPage = () => {
  const user = {
    username: "jjyunwang2021",
    avatarUrl: "https://avatars.githubusercontent.com/u/1234567?v=4", // 替换为真实头像地址
    appName: "Ionic by Ionic",
    repoAccess: "Public and private",
    emailAccess: "Email addresses (read-only)",
    orgAccess: {
      name: "themeselection",
      allowed: false,
    },
    redirectUrl: "https://ionic.io",
  };

  return (
    <Box
      display="flex"
      flexDirection="column"
      alignItems="center"
      justifyContent="center"
      minHeight="100vh"
      bgcolor="#f6f8fa"
      px={2}
    >
      <Box display="flex" alignItems="center" mb={4}>
        <Avatar sx={{ bgcolor: "black", mr: 2 }}>O</Avatar>
        <GitHubIcon sx={{ fontSize: 40, color: "black" }} />
      </Box>
      <Typography variant="h5" gutterBottom>
        Authorize {user.appName}
      </Typography>

      <Card sx={{ maxWidth: 500, width: "100%", mt: 2 }}>
        <CardContent>
          <Box display="flex" alignItems="center" mb={2}>
            <Avatar src={user.avatarUrl} />
            <Typography variant="subtitle1" ml={2}>
              wants to access your <strong>{user.username}</strong> account
            </Typography>
          </Box>

          <Divider sx={{ my: 2 }} />

          <Typography variant="subtitle2" gutterBottom>
            Repositories
          </Typography>
          <Typography variant="body2">{user.repoAccess}</Typography>

          <Divider sx={{ my: 2 }} />

          <Typography variant="subtitle2" gutterBottom>
            Personal user data
          </Typography>
          <Typography variant="body2">{user.emailAccess}</Typography>

          <Divider sx={{ my: 2 }} />

          <Typography variant="subtitle2" gutterBottom>
            Organization access
          </Typography>
          <Typography variant="body2" color="error">
            {user.orgAccess.name} — Disallowed by org owner
          </Typography>
        </CardContent>
        <Box display="flex" justifyContent="space-between" px={2} pb={2}>
          <Button variant="outlined" color="inherit">
            Cancel
          </Button>
          <Button
            variant="contained"
            color="success"
            onClick={() => (window.location.href = user.redirectUrl)}
          >
            Authorize ionic-team
          </Button>
        </Box>
      </Card>

      <Typography variant="caption" mt={4}>
        Not owned or operated by GitHub. More than 1K GitHub users.
      </Typography>
    </Box>
  );
};



GitHubAuthPage.getLayout = (page: ReactNode) => <BlankLayout>{page}</BlankLayout>

GitHubAuthPage.guestGuard = true

export default GitHubAuthPage
