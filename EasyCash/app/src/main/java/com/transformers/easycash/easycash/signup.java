package com.transformers.easycash.easycash;

import android.content.Intent;
import android.graphics.Color;
import android.graphics.Typeface;
import android.support.design.widget.Snackbar;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.EditText;
import android.widget.TextView;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class signup extends AppCompatActivity {
    EditText mail, mophone, pswd, usrusr;
    TextView lin, sup;
    private static final String RESPONSELOG = "LogResponse";

    RequestQueue queue;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_signup);
        queue = Volley.newRequestQueue(this);

        sup = (TextView) findViewById(R.id.sup);
        lin = (TextView) findViewById(R.id.lin);
        usrusr = (EditText) findViewById(R.id.usrusr);
        pswd = (EditText) findViewById(R.id.pswrdd);
        mail = (EditText) findViewById(R.id.mail);
        mophone = (EditText) findViewById(R.id.mobphone);
        Typeface custom_font = Typeface.createFromAsset(getAssets(), "fonts/LatoLight.ttf");
        Typeface custom_font1 = Typeface.createFromAsset(getAssets(), "fonts/LatoRegular.ttf");
        mophone.setTypeface(custom_font);
        sup.setTypeface(custom_font1);
        pswd.setTypeface(custom_font);
        lin.setTypeface(custom_font);
        usrusr.setTypeface(custom_font);
        mail.setTypeface(custom_font);


        sup.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                //Intent it = new Intent(signup.this, login.class);
//                startActivity(it);

                if (mail.getText().toString().isEmpty() || mail.getText().toString() == null) {
                    mail.setError("Email is required");
                } else if (pswd.getText().toString().isEmpty() || pswd.getText().toString() == null) {
                    pswd.setError("Password is required");
                } else {

                    String url = "http://api.recodenigeria.tk/api/v1/customer/register";
                    HashMap<String, String> params = new HashMap<String, String>();
                    params.put("name", usrusr.getText().toString());
                    params.put("email", mail.getText().toString());
                    params.put("phone", mophone.getText().toString());
                    params.put("password", pswd.getText().toString());
                    JSONObject jsonBody = new JSONObject(params);

                    JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST, url, jsonBody, new Response.Listener<JSONObject>() {
                        @Override
                        public void onResponse(JSONObject response) {
                            Log.i(RESPONSELOG, response.toString());
                            Intent it = new Intent(signup.this, DashBoardActivity.class);
                            startActivity(it);
                        }
                    }, new Response.ErrorListener() {
                        @Override
                        public void onErrorResponse(VolleyError error) {
                            Log.i(RESPONSELOG, error.toString());
                            Snackbar.make(findViewById(R.id.signupCoordLayout), "Email or Phone number already in use", Snackbar.LENGTH_SHORT).show();
                        }
                    });


                    queue.add(jsonObjectRequest);

                }
            }
        });
        lin.setLinksClickable(true);
        lin.setLinkTextColor(Color.BLUE);
        lin.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent it = new Intent(signup.this, login.class);
                startActivity(it);
            }
        });
    }
}
