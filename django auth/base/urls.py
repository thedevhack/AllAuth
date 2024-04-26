from django.urls import path
from .views import home, signin, logout_
from .views import SignUpView

urlpatterns = [
    path("", home, name="home"),
    path("signin/", signin, name="signin"),
    path("signup/", SignUpView.as_view(), name="signup"),
    path("logout/", logout_, name="logout"),
]