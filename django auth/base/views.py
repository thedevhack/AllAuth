from django.shortcuts import render, redirect
from django.views.generic.edit import CreateView
from django.urls import reverse_lazy
from .forms import CustomUserCreationForm
from django.http import HttpResponseBadRequest
from django.contrib import messages
from .models import User
from django.contrib.auth import login, authenticate, logout

def home(request):

     return render(request, "base/home.html", {})


class SignUpView(CreateView):
    form_class = CustomUserCreationForm
    success_url = reverse_lazy("signin")
    template_name = "base/signup.html"


def signin(request):

    if request.method == "POST":

        email = request.POST.get("email")
        password = request.POST.get("password")
        print("1")
        if email is None or password is None:
            return HttpResponseBadRequest()

        print("2")
        if not User.objects.filter(email=email).exists():
            messages.error(request, "Invalid email")
            return redirect('login')
        print("3")

        user = authenticate(email=email, password=password)
        print("4")
        if user is None:
            print("5")
            messages.error(request, "Invalid password")
            return redirect('login')
        else:
            print("6")
            login(request, user)
            return redirect('home')

    return render(request, "base/signin.html")



def logout_(request):
    logout(request)
    return redirect('signin')
